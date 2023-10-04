<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDescription extends Model
{
    protected $fillable = ["tenant_id", "tenant_name", "description", "quantity", "price", "amount", "sale_id"];
    use HasFactory;
}
