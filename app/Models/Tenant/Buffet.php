<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Buffet extends Model
{
    protected $fillable = ['name', 'branch_id'];

    protected $casts = [
        'name' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function buffetItems()
    {
        return $this->hasMany(BuffetItem::class);
    }
}
