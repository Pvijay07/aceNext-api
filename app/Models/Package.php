<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        'price_display',
        'users',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'float',
        'users' => 'integer',
    ];
}
