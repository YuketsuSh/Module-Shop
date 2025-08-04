<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'shop_carts';

    protected $fillable = ['user_id', 'is_active', 'total'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items->sum(fn($item) => $item->price * $item->quantity);
        $this->save();
    }
}
