<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{

    /**
     * Summary of users
     * @param mixed $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function users($request)
    {
        $search = $request->search;

        return User::whereNot('id', Auth::id()) // Exclude the logged-in user
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 10);
    }
    
    /**
     * Summary of destroy
     * @param mixed $id
     */
    public function destroy($id)
    {
        $user = User::whereNot('id',Auth::user()->id)->findOrFail($id);
        return $user->delete();
    }

    /**
     * Summary of update
     * @param mixed $id
     * @param mixed $request
     * @return bool
     */
    public function update($id,$request){
        return User::where('id',$id)->update($request);
    }

    public function store($request){
        return User::create($request);
    }
}
