<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'duration_type',
        'package_type',
        'price',
        'features',
        'image',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'status' => 'boolean',
    ];
}
