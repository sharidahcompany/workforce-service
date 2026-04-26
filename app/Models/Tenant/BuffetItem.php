<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class BuffetItem extends Model
{

    protected $fillable = [
        'buffet_id',
        'name',
        'description',
        'price',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    public function buffet()
    {
        return $this->belongsTo(Buffet::class, 'buffet_id');
    }

    public function orderItems()
    {
        return $this->hasMany(BuffetOrderItem::class, 'buffet_item_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('buffet_items')->singleFile();
    }
}
