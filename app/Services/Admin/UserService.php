<?php

namespace App\Services\Admin;

use App\Constant\AdminConstant;
use App\Helper\PaginationHelper;
use App\Models\Admin\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{

    /**
     * Summary of users
     * @param mixed $request
     * @return LengthAwarePaginator
     */
    public function users(Request $request): LengthAwarePaginator
    {
        $search = $request->search;

        $options = PaginationHelper::pageQueryOptions($request);
        $column = $options->column;
        $direction = $options->direction;
        $perPage = $options->perPage;

        return User::whereNotIn('id', [Auth::id(), AdminConstant::DEFAULT_ADMIN_ID])
            ->when($search, function ($query, $search) {
                $query->whereAny(
                    [
                        'name',
                        'email'
                    ],
                    'like',
                    "%{$search}%"
                );
            })
            ->orderBy($column, $direction)
            ->paginate($perPage);
    }

    /**
     * Summary of destroy
     * @param int $id
     * @return bool|null
     */
    public function destroy(int $id)
    {
        $user = User::whereNot('id', Auth::user()->id)->findOrFail($id);
        return $user->delete();
    }

    /**
     * Summary of update
     * @param mixed $id
     * @param mixed $request
     * @return bool
     */
    public function update(int $id, $request)
    {
        return User::where('id', $id)->update($request);
    }

    /**
     * Summary of store
     * @param mixed $request
     * @return User
     */
    public function store($request): User
    {
        return User::create($request);
    }
}
