<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Tenant\Branch;
use App\Models\Tenant\User\User;
use Illuminate\Support\Facades\Log;

class CreateUser
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
    public function handle(UserCreated $event): void
    {
        $tenantId = $event->tenantId;
        $userData = $event->userData;

        if ($tenantId) {
            tenancy()->initialize($tenantId);
        }

        $user = User::create($userData);
        $branch = Branch::create([
            'name' => [
                'en' => 'Headquarter',
                'ar' => 'المقر الرئيسي'
            ]
        ]);

        $user['branch_id'] = $branch->id;
        $user->save();
    }
}
