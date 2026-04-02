<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class BuffetOrderItem extends Model
{
    protected $fillable = [
        'buffet_order_id',
        'buffet_item_id',
        'quantity',
        'price',
        'total',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(BuffetOrder::class, 'buffet_order_id');
    }

    public function item()
    {
        return $this->belongsTo(BuffetItem::class, 'buffet_item_id');
    }
}
