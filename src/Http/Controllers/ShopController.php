<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function dashboard(Request $request)
    {
        $to   = $request->query('to')   ? Carbon::parse($request->query('to'))->endOfDay()   : Carbon::now()->endOfDay();
        $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();

        $kpis = [
            'revenue'          => 0.0,
            'orders_today'     => 0,
            'abandoned_carts'  => 0,
            'avg_order'        => 0.0,
        ];

        $sales        = [];
        $topProducts  = [];
        $recentOrders = [];

        if (Schema::hasTable('shop_orders')) {
            $revenue = DB::table('shop_orders')
                ->where('status', 'paid')
                ->whereBetween('created_at', [$from, $to])
                ->sum('total');

            $ordersToday = DB::table('shop_orders')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $ordersCount = DB::table('shop_orders')
                ->where('status', 'paid')
                ->whereBetween('created_at', [$from, $to])
                ->count();

            $kpis['revenue']      = (float) $revenue;
            $kpis['orders_today'] = (int) $ordersToday;
            $kpis['avg_order']    = $ordersCount > 0 ? (float) $revenue / $ordersCount : 0.0;

            $sales = DB::table('shop_orders')
                ->selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->where('status', 'paid')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(fn ($r) => ['date' => $r->date, 'total' => (float) $r->total])
                ->all();

            $recentOrders = DB::table('shop_orders')
                ->select(['id', 'customer_name', 'total', 'status', 'created_at'])
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(function ($o) {
                    return [
                        'id'         => (string) $o->id,
                        'customer'   => $o->customer_name ?: 'Invité',
                        'total'      => (float) $o->total,
                        'status'     => $o->status,
                        'created_at' => $o->created_at->format('Y-m-d H:i'),
                        'show_url'   => route('admin.shop.orders.show', $o->id),
                    ];
                })
                ->all();
        }

        if (Schema::hasTable('shop_carts')) {
            $kpis['abandoned_carts'] = (int) DB::table('shop_carts')
                ->where('is_active', false)
                ->whereBetween('updated_at', [$from, $to])
                ->count();
        }

        if (Schema::hasTable('shop_order_items')) {
            $topProducts = DB::table('shop_order_items')
                ->selectRaw('COALESCE(product_name, CONCAT("Produit #", product_id)) as name, SUM(quantity) as qty, SUM(total) as revenue')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('name')
                ->orderByDesc(DB::raw('SUM(quantity)'))
                ->limit(5)
                ->get()
                ->map(fn ($r) => ['name' => $r->name, 'qty' => (int) $r->qty, 'revenue' => (float) $r->revenue])
                ->all();
        }

        return view('shop::admin.index', compact('kpis', 'sales', 'topProducts', 'recentOrders'));
    }
}
