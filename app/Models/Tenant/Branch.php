<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use App\Models\Tenant\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'code',
        'building_number',
        'street',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'radius',
        'manager_id',
    ];

    protected $casts = [
        'name' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'integer',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
