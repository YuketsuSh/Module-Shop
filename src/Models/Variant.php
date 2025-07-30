<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table = 'shop_variants';
    protected $fillable = ['product_id', 'name', 'price_modifier', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
