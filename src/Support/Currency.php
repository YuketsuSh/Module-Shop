<?php

namespace Modules\Shop\Support;

class Currency
{
    public static function all(): array
    {
        return [
            'EUR' => 'Euro',
            'USD' => 'Dollar américain',
            'GBP' => 'Livre sterling',
            'JPY' => 'Yen japonais',
            'CAD' => 'Dollar canadien',
            'AUD' => 'Dollar australien',
            'CHF' => 'Franc suisse',
            'CNY' => 'Yuan chinois',
            'SEK' => 'Couronne suédoise',
            'NZD' => 'Dollar néo-zélandais',
        ];
    }
}
