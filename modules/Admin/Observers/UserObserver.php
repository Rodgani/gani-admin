<?php

declare(strict_types=1);

namespace Modules\Admin\Observers;

use App\Constants\AdminConstants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Modules\Admin\Models\User;

final class UserObserver
{
    /**
     * Handle the User "deleted" event.
     */
    public function deleting(User $user): void
    {
        if ($user->id === AdminConstants::DEFAULT_ADMIN_ID) {
            throw ValidationException::withMessages([
                'user' => 'The default admin cannot be deleted.',
            ]);
        }

        if (Auth::user()?->id === $user->id) {

            throw ValidationException::withMessages([
                'user' => 'The current authenticated user cannot be deleted.',
            ]);
        }
    }
}
