<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;

class BuffetOrder extends Model
{
    protected $fillable = [
        'user_id',
        'notes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(BuffetOrderItem::class, 'buffet_order_id');
    }
}
