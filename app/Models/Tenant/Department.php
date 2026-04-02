<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use App\Models\Tenant\Branch;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'parent_id',
        'manager_id',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with([
            'branch',
            'manager',
            'parent',
            'childrenRecursive',
        ]);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
