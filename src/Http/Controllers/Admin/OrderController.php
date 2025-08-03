<?php

namespace Modules\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shop\Enum\OrderStatus;
use Modules\Shop\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('shop::admin.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
            'activeStatus' => $request->status
        ]);

    }

    public function show(Order $order)
    {
        $order->load('user', 'products');

        return view('shop::orders.show', [
            'order' => $order,
            'statuses' => OrderStatus::cases()
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(OrderStatus::cases(), 'value')),
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }
}
