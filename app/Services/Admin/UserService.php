<?php

namespace App\Services\Admin;

use App\Models\User;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function users($request){
        return User::paginate($request->per_page ?? 10);
    }
}
