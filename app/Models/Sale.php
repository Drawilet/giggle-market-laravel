<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "amount", "payment_id", "payment_method", "payment_status"];

    public function descriptions()
    {
        return $this->hasMany(SaleDescription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
