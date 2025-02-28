<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Admin\UserService $service
     */
    public function __construct(protected UserService $service){}

    /**
     * Summary of userIndex
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function userIndex(Request $request){
        $data = $this->service->users($request);
        return Inertia::render('users/users-index',[
            "users" => $data
        ]);
    }

    /**
     * Summary of destroy
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){
        $this->service->destroy($id);
        return Redirect::route('user.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Summary of update
     * @param mixed $id
     * @param \App\Http\Requests\Admin\Users\UserUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id,UserUpdateRequest $request){
        $this->service->update($id,$request->all());
        return Redirect::route('user.index')->with('success', 'User updated successfully.');
    }
}
