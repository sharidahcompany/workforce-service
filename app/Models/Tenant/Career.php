<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = ['title'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
