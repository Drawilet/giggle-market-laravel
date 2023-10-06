<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Base extends Model
{
    use HasFactory;
    public function tenant()
    {
        return  $this->belongsTo(Tenant::class);
    }
}
