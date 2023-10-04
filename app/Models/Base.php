<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Base extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope("ancient", function (Builder $builder) {
            $user = Auth::user();

            if ($user->tenant_id) $builder->where("tenant_id", $user->tenant_id);
            else $builder;
        });
    }

    public function tenant()
    {
        return  $this->belongsTo(Tenant::class);
    }
}
