<?php

namespace App\Repositories\Admin;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use App\Models\Admin\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function users($request): LengthAwarePaginator
    {
        $search = $request->search;

        $option = PaginationHelper::pageQueryOptions($request);

        return User::with('role:slug,name')
            ->whereNotIn('id', [Auth::id(), AdminConstants::DEFAULT_ADMIN_ID])
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
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function destroy(User $user): bool|null
    {
        return $user->delete();
    }

    public function update(User $user, $request): bool
    {
        return $user->update($request);
    }

    public function store($request): User
    {
        return User::create($request);
    }
}
