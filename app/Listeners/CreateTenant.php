<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Events\UserCreated;
use App\Models\Central\Tenant\Tenant;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CreateTenant
{
    public function handle(TenantCreated $event): void
    {
        $data = $event->data;
        $userData = $data['user'];
        $tenantId = $data['tenant_id'];

        // Create tenant in central DB
        $tenantModel = Tenant::create([
            'id' => $tenantId
        ]);
        Log::info("Tenant created in accounting DB: ");


        Event::dispatch(new UserCreated($userData, $tenantId));
    }
}
