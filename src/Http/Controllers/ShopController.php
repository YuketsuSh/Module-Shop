<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Modules\Shop\Enum\OrderStatus;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\Product;

class ShopController extends Controller
{
    public function dashboard(Request $request)
    {
        $to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay();
        $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : now()->subDays(29)->startOfDay();

        $orders = Order::whereBetween('created_at', [$from, $to]);
        $paidOrders = (clone $orders)->where('status', OrderStatus::Paid);

        $revenue = $paidOrders->sum('total');
        $ordersCount = $paidOrders->count();
        $ordersToday = Order::whereDate('created_at', now())->count();

        $avgOrder = $ordersCount > 0 ? $revenue / $ordersCount : 0;

        $kpis = [
            'revenue' => round($revenue, 2),
            'orders_today' => $ordersToday,
            'avg_order' => round($avgOrder, 2),
            'abandoned_carts' => DB::table('shop_carts')
                ->where('is_active', false)
                ->whereBetween('updated_at', [$from, $to])
                ->count(),
        ];

        $sales = Order::where('status', OrderStatus::Paid)
            ->whereBetween('created_at', [$from, $to])
            ->get()
            ->groupBy(fn ($order) => $order->created_at->toDateString())
            ->map(fn ($dayOrders, $date) => [
                'date' => $date,
                'total' => round($dayOrders->sum('total'), 2),
            ])->values()->all();

        $recentOrders = Order::latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => (string) $order->id,
                    'customer' => optional($order->user)->name ?? 'Invité',
                    'total' => round($order->total, 2),
                    'status' => $order->status->value,
                    'created_at' => $order->created_at->format('Y-m-d H:i'),
                    'show_url' => route('admin.shop.orders.show', $order->id),
                ];
            })->all();

        // Top products (using pivot)
        $topProducts = DB::table('shop_order_product')
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(price * quantity) as revenue'))
            ->join('shop_orders', 'shop_orders.id', '=', 'shop_order_product.order_id')
            ->where('shop_orders.status', OrderStatus::Paid->value)
            ->whereBetween('shop_orders.created_at', [$from, $to])
            ->groupBy('product_id')
            ->orderByDesc(DB::raw('SUM(quantity)'))
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $product = Product::find($row->product_id);
                return [
                    'name' => $product?->name ?? ("Produit #{$row->product_id}"),
                    'qty' => (int) $row->qty,
                    'revenue' => round($row->revenue, 2),
                ];
            })
            ->all();

        return view('shop::admin.index', compact('kpis', 'sales', 'topProducts', 'recentOrders'));
    }
}
