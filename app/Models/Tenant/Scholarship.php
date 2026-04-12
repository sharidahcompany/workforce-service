<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_percentage',
        'duration',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
