<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Base
{
    use HasFactory;

    protected $fillable = ["name", "percentage", "tenant_id"];
}
