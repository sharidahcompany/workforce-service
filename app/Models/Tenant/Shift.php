<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'work_days',
        'start_time',
        'end_time',
        'grace_period',
        'overtime_allowed',
        'overtime_rate',
        'deduction_rate',
    ];

    protected $casts = [
        'work_days' => 'array',
        'grace_period' => 'integer',
        'overtime_allowed' => 'boolean',
        'overtime_rate' => 'decimal:2',
        'deduction_rate' => 'decimal:2',
    ];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shift_user')
            ->withTimestamps();
    }
}
