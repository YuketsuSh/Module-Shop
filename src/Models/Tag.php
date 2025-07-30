<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'shop_tags';
    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shop_product_tag');
    }
}
