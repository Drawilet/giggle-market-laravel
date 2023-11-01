<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDescription extends Base
{
    protected $fillable = [
        "store_id",
        "product_id",
        "quantity",
        "price",
        "sale_id",
        "is_reviewed",
    ];
    use HasFactory;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
