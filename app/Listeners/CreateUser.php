<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Career;
use App\Models\Tenant\User\User;

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

        if($user->id === 1) {
        $branch = Branch::create([
            'name' => [
                'en' => 'Headquarter',
                'ar' => 'المقر الرئيسي'
            ]
        ]);

        $user['branch_id'] = $branch->id;
        $user->save();

        $career = Career::create([
            'name' => [
                'ar'=>'الرئيس',
                'en'=>'CEO',
            ],
            'description'=>[
                'ar'=>'الرئيس',
                'en'=>'CEO',
            ],
        ]);

        $user['career_id'] = $career->id;
        $user->save();
        }
    }
}
