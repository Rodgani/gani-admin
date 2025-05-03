<?php

namespace Modules\Admin\Observers;

use App\Constants\AdminConstants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Modules\Admin\Models\User;

class UserObserver
{

    public function created(User $user)
    {
        Password::sendResetLink(["email" => $user->email]);
    }
    /**
     * Handle the User "deleted" event.
     */
    public function deleting(User $user): void
    {
        if (Auth::user()->id === $user->id) {

            throw ValidationException::withMessages([
                'user' => 'The user who is currently authenticated cannot be deleted.',
            ]);
        }

        if($user->id === AdminConstants::DEFAULT_ADMIN_ID){
            throw ValidationException::withMessages([
                'user' => 'The default admin cannot be deleted.',
            ]);
        }

    }

}
