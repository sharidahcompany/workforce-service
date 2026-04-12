<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'organization',
        'description',
        'start_date',
        'end_date',
        'type',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
