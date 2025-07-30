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
            strtolower('Shop') => [
                'name' => 'Shop',
                'type' => 'link',
                'icon' => 'bi bi-box',
                'route' => 'admin.' . strtolower('Shop') . '.index',
            ],
        ];
    }
}