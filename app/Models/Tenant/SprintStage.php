<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class SprintStage extends Model
{
    protected $fillable = [
        'sprint_id',
        'name',
        'order',
    ];

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }
}
