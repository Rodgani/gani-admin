<?php

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\User;

class UserRepository
{
    public function __construct(private User $userModel)
    {
    }
    public function users($request): LengthAwarePaginator
    {
        $search = $request->search;

        $option = PaginationHelper::pageQueryOptions($request);

        return $this->userModel->with('role')
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

    public function destroyUser(User $user): bool|null
    {
        return $user->delete();
    }

    public function updateUser(User $user, $request): bool
    {
        return $user->update($request);
    }

    public function storeUser($request): User
    {
        return $this->userModel->create($request);
    }
}
