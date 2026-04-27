<?php

namespace App\Models\Tenant;

use App\Enums\ProjectManagment\SprintStatus;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status'=>SprintStatus::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function stages()
    {
        return $this->hasMany(SprintStage::class)->orderBy('order');
    }
}
