<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Base
{
    use HasFactory;
    protected $fillable = [
        "tenant_id",
        "description",
        "price",
        "stock",
        "category_id",
        "photo",
    ];

    public function category()
    {
        return  $this->belongsTo(Category::class);
    }

    public function product_taxes()
    {
        return $this->hasMany(ProductTax::class);
    }
}
