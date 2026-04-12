<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRequest extends Model
{
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'title',
        'description',
        'price',
        'discount_percentage',
        'duration',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
