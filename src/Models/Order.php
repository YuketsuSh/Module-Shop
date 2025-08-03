<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shop\Enum\OrderStatus;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'reference',
        'total',
        'currency',
        'status',
        'payment_method',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'paid_at' => 'datetime',
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'shop_order_product')->withPivot('quantity', 'price')->withTimestamps();
    }
}
