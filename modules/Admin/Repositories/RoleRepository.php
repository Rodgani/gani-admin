<?php

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Role;

class RoleRepository
{
    public function __construct(private Role $model)
    {
    }
    public function roles(): Collection
    {
        return $this->model->select('id', 'slug', 'name')->get();
    }

    public function paginatedRoles(object $request): LengthAwarePaginator
    {
        $search = $request->search ?? null;
        $option = PaginationHelper::pageQueryOptions($request);

        return $this->model->when($search, function ($query, $search) {
            $query->whereAny(['name', 'slug'], 'like', "%{$search}%");
        })
            ->when(Auth::user()->id != AdminConstants::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstants::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function storeRole(array $request): Role
    {
        return $this->model->create($request);
    }

    public function updateRole(int $id, array $request): bool
    {
        return $this->model->findOrFail($id)->update($request);
    }
}
