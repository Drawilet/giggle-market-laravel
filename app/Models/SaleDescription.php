<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDescription extends Base
{
    protected $fillable = [
        "store_id",
        "description",
        "quantity",
        "price",
        "sale_id"
    ];
    use HasFactory;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
