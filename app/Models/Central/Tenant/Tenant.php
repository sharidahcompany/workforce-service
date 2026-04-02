<?php

namespace App\Models\Central\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase, HasMedia
{
    use HasDatabase, HasDomains, InteractsWithMedia, HasUuids;

    protected $fillable = [
        'id',
        'data',
    ];


    protected $keyType = 'string';
    public $incrementing = false;
    public $table = 'tenants';

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'data',
        ];
    }
}
