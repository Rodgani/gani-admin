<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}
    public function userIndex(Request $request){
        $data = $this->service->users($request);
        return Inertia::render('users/users-index',[
            "users" => $data
        ]);
    }
}
