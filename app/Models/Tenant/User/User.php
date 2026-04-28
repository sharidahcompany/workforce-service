<?php

namespace App\Models\Tenant\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Career;
use App\Models\Tenant\Department;
use App\Models\Tenant\Experience;
use App\Models\Tenant\JobApplication;
use App\Models\Tenant\Mission;
use App\Models\Tenant\Project;
use App\Models\Tenant\Shift;
use App\Models\Tenant\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements HasMedia, JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'external_id',
        'branch_id',
        'department_id',
        'career_id',
        'email',
        'phone',
        'id_number',
        'first_name',
        'last_name',
        'full_name',
        'address',
        'nationality',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }



    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */

    //start relationships


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'manager_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function departments()
    {
        return $this->hasMany(Department::class, 'manager_id');
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_id');
    }

    public function careerApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function shifts(): BelongsToMany
    {
        return $this->belongsToMany(Shift::class, 'shift_user');
    }

    public function missions(): BelongsToMany
    {
        return $this->belongsToMany(Mission::class, 'mission_user')
            ->withTimestamps();
    }



    //end relationships

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->external_id;
    }
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        return 'external_id';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
}
