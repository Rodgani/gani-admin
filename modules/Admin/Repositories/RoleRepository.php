<?php

namespace Modules\Admin\Repositories;

use App\Constants\AdminConstants;
use App\Helpers\PaginationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

    public function paginatedRoles($request): LengthAwarePaginator
    {
        $search = $request->search ?? null;
        $option = PaginationHelper::pageQueryOptions($request);

        return $this->model->when($search, function ($query, $search) {
            $query->whereAny(['name', 'slug'], 'like', "%{$search}%");
        })
            ->when($request->user()->id != AdminConstants::DEFAULT_ADMIN_ID, function ($query) {
                $query->whereNot('id', AdminConstants::DEFAULT_ROLE_ID);
            })
            ->orderBy($option->column, $option->direction)
            ->paginate($option->perPage);
    }

    public function storeRole($request): Role
    {
        return $this->model->create($request);
    }

    public function updateRole(int $id, $request): bool
    {
        // here you are updating an existing Role instance
        return $this->model->findOrFail($id)->update($request);
    }
}
