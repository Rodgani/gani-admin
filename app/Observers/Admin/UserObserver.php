<?php

namespace App\Observers\Admin;

use App\Constants\AdminConstant;
use App\Models\Admin\User;
use Illuminate\Validation\ValidationException;

class UserObserver
{

    /**
     * Handle the User "deleted" event.
     */
    public function deleting(User $user): void
    {
        if ($user->id === AdminConstant::DEFAULT_ADMIN_ID) {

            throw ValidationException::withMessages([
                'user' => 'The default admin user cannot be deleted.',
            ]);
        }

    }

}
