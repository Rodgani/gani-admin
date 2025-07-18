<?php

declare(strict_types=1);

namespace Modules\Authentication\Repositories;

use Modules\Admin\Models\User;

final class RegisterRepository
{
    public function storeUser(array $request): User
    {
        return User::create($request);
    }
}