<?php

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\User;

class UserRepository
{
    public function users(object $request): LengthAwarePaginator
    {
        $search = $request->search ?? null;
        $option = PaginationHelper::pageQueryOptions($request);

        return User::with('role')
            ->whereNotIn('id', [AdminConstants::DEFAULT_ADMIN_ID])
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

    public function destroyUser(int $id): bool|null
    {
        return User::findOrFail($id)->delete();
    }

    public function updateUser(int $id, array $request): bool
    {
        return User::findOrFail($id)->update($request);
    }

    public function storeUser(array $request): User
    {
        return User::create($request);
    }
}
