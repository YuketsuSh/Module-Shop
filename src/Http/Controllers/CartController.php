<?php
namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shop\Models\Cart;
use Modules\Shop\Models\CartItem;
use Modules\Shop\Models\Product;
use Modules\Shop\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function show()
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'is_active' => true],
            ['total' => 0]
        );

        $cart->load('items.product');
        $cart->recalculateTotal();

        $settings = Setting::get('shop.general', []);
        $taxRate = isset($settings['tax_rate']) ? (float) $settings['tax_rate'] : 0;
        $taxEnabled = !empty($settings['tax_enabled']);

        $taxAmount = $taxEnabled ? $cart->total * ($taxRate / 100) : 0;
        $totalWithTax = $cart->total + $taxAmount;

        return view('shop::cart.index', [
            'items'    => $cart->items,
            'subtotal' => $cart->total,
            'tax'      => round($taxAmount, 2),
            'total'    => round($totalWithTax, 2),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:shop_products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'is_active' => true],
            ['total' => 0]
        );

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        $cart->load('items');
        $cart->recalculateTotal();

        return response()->json(['message' => 'Produit ajouté au panier.']);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        $item->cart->recalculateTotal();

        return response()->json(['message' => 'Quantité mise à jour.']);
    }

    public function remove($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $cart = $item->cart;
        $item->delete();

        $cart->recalculateTotal();

        return response()->json(['message' => 'Produit retiré du panier.']);
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->where('is_active', true)->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->recalculateTotal();
        }

        return response()->json(['message' => 'Panier vidé.']);
    }
}
