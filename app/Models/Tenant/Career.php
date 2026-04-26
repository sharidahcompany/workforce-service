<?php

namespace App\Models\Tenant;

use App\Enums\CareerStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Career extends Model  implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['department_id','name','description','status'];
    

    // start Relationship
        public function department(){
            return $this->belongsTo(Department::class,'department_id','id');
        }
        public function benefits(){
            return $this->belongsToMany(Benefit::class);
        }
    // end Relationship

    protected $casts =[
        'department_id'=>'integer',
        'name' => 'array',
        'description' => 'array',
        'status'=>CareerStatus::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('career')->singleFile();
    }

}
