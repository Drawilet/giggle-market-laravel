<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["payer_name", "payer_id", "payer_type", "recipient_id", "recipient_name", "recipient_type", "amount", "description"];

    public function payer()
    {
        return $this->morphTo();
    }
    public function recipient()
    {
        return $this->morphTo();
    }
}
