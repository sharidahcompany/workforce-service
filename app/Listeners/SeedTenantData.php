<?php

namespace App\Listeners;

use App\Events\TenantCreated;


class SeedTenantData
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        $data = $event->data;
        $tenantId = $data['tenant_id'];
    }
}
