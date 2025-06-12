<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Admin\Http\Requests\Users\UserDeleteAccountRequest;
use Modules\Admin\Http\Requests\Users\UserProfileUpdateRequest;
use Modules\Admin\Services\UserProfileService;
use Modules\Controller;

final class ProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService
    ){}
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function update(UserProfileUpdateRequest $request): RedirectResponse
    {
        $this->userProfileService->update($request);
        return back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(UserDeleteAccountRequest $request): RedirectResponse
    {
        $this->userProfileService->destroy($request);
        return redirect('/');
    }
}
