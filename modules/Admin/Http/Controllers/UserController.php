<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Controllers;

use App\Helpers\CountryHelper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\Http\Requests\Users\UserCreateRequest;
use Modules\Admin\Http\Requests\Users\UserDeleteRequest;
use Modules\Admin\Http\Requests\Users\UserIndexRequest;
use Modules\Admin\Http\Requests\Users\UserUpdateRequest;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Repositories\RoleRepository;
use Modules\Admin\Repositories\UserRepository;
use Modules\Controller;

final class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository
    ) {}

    public function index(UserIndexRequest $request): Response
    {
        $users = $this->userRepository->getUsers($request->validatedObject());
        $roles = $this->roleRepository->getRoles();

        return Inertia::render('admin/user/index', [
            "users" => $users,
            "roles" => $roles,
            "countries" => CountryHelper::getAll()
        ]);
    }

    public function destroy(UserDeleteRequest $request): RedirectResponse
    {
        $this->userRepository->destroyUser((int) $request->validated()['id']);
        return back();
    }

    public function update(UserUpdateRequest $request): RedirectResponse
    {
        $userData = $request->validated();
        $this->userRepository->updateUser((int) $userData['id'], $userData);
        return back();
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $userData = $request->validated();
        $user = $this->userRepository->storeUser($userData);
        event(new Registered($user));
        return back();
    }
}
