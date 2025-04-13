<?php

namespace Modules\Authentication\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\User;

class RegisterRepository
{
    public function storeUser($request)
    {
        return User::create($request);
    }
}