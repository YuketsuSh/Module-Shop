<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'shop_products';
    protected $fillable = [
        'name', 'slug', 'description', 'type', 'price', 'currency',
        'stock', 'is_featured', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'shop_product_tag');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function versions()
    {
        return $this->hasMany(ProductVersion::class);
    }
}
