<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualVacation extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'balance',
        'used',
        'remaining',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'year' => 'integer',
        'balance' => 'integer',
        'used' => 'integer',
        'remaining' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
