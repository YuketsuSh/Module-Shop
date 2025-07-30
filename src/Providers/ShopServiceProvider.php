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
                'name' => 'Boutique',
                'type' => 'dropdown',
                'icon' => 'fa-shopping-cart',
                'items' => [
                    'admin.shop.dashboard' => [
                        'name' => 'Dashboard',
                        'icon' => 'fa-chart-line'
                    ],
                    'admin.shop.products.index' => [
                        'name' => 'Produits',
                        'icon' => 'fa-box'
                    ],
                    'admin.shop.categories.index' => [
                        'name' => 'Catégories',
                        'icon' => 'fa-folder-open'
                    ],
                    'admin.shop.tags.index' => [
                        'name' => 'Tags',
                        'icon' => 'fa-tags'
                    ],
                    'admin.shop.orders.index' => [
                        'name' => 'Commandes',
                        'icon' => 'fa-receipt'
                    ],
                    'admin.shop.settings.index' => [
                        'name' => 'Paramètres',
                        'icon' => 'fa-cogs'
                    ],
                ]
            ],
        ];
    }

}
