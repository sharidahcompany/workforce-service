<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CareerPost extends Model
{
    protected $fillable = [
        'career_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];


    // start relationship
        public function career(){
            return $this->belongsTo(Career::class,'career_id','id');
        } 
    // end relationship
    protected $casts = [
        'career_id'=>'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
