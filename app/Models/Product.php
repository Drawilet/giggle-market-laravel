<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "tenant_id",
        "user_id",
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

    public function tenant()
    {
        return  $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
}
