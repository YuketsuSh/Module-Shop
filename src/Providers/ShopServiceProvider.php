<?php

namespace Modules\Shop\Providers;

use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }

    public function adminNavigation(): array
    {
        return [
            'shop' => [
                'name' => 'Shop',
                'type' => 'dropdown',
                'icon' => 'fa-shopping-cart',
                'items' => [
                    'admin.shop.dashboard' => ['name' => 'Dashboard'],
                ]
            ],
        ];
    }
}
