<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDescription extends Base
{
    protected $fillable = [
        "tenant_id",
        "tenant_name",
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
