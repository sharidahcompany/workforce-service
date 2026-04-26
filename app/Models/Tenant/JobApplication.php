<?php

namespace App\Models\Tenant;

use App\Enums\JobApplicationStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobApplication extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'recommended_by',
        'career_post_id',
        'user_id',
        'accepted_by',
        'confirmed_by',
        'status',
    ];

// start Relationship
    public function recommendeder()
    {
        return $this->belongsTo(User::class,'recommended_by','id');
    }
    public function careerPost(): BelongsTo
    {
        return $this->belongsTo(CareerPost::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function acceptedBy()
    {
        return $this->belongsTo(User::class,'accepted_by','id');
    }
    public function confirmedBy(){
        return $this->belongsTo(User::class,'confirmed_by','id');
    }
// end Relationship

    protected $casts = [
        'is_offer_sent'=>'',
        'recommended_by'=>'integer',
        'career_post_id'=>'integer',
        'user_id'=>'integer',
        'accepted_by'=>'integer',
        'confirmed_by'=>'integer',
        'status'=>JobApplicationStatus::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cv')->singleFile();
    }
}
