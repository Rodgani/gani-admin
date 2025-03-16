<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Admin\RoleService $service
     */
    public function __construct(
        protected RoleService $service
    ) {

    }

    /**
     * Summary of roleIndex
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function roleIndex(Request $request)
    {
        $roles = $this->service->paginatedRoles($request);
        return Inertia::render('roles/roles-index', [
            "roles" => $roles
        ]);
    }
}
