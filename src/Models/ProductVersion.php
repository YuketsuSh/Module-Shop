<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVersion extends Model
{

    protected $table = 'shop_product_versions';

    protected $fillable = [
        'product_id', 'version', 'changelog',
        'file_path', 'file_hash', 'ttl',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
