<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Benefit extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'description',
    ];

    // start Relationship
        public function careers()
        {
            return $this->belongsToMany(Career::class);
        }
    // end Relationship


    protected $casts = [
        'name'=>'array',
        'description'=>'array',
    ];

}
