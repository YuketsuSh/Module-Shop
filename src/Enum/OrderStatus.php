<?php

namespace Modules\Shop\Enum;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';


    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'En attente',
            self::Paid      => 'Payée',
            self::Failed    => 'Échouée',
            self::Cancelled => 'Annulée',
            self::Refunded  => 'Remboursée',
        };
    }
}
