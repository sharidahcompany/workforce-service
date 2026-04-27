<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['user_id','commentable','body'];


//start relationships
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
//end relationships




    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment');
    }
}
