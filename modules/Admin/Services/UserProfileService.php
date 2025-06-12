<?php

declare(strict_types=1);

namespace Modules\Admin\Services;

use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\UserRepository;

final class UserProfileService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageService $imageService
    ) {}
    public function update($request): bool
    {
        $user = $request->user();
        $data = $request->safe()->except('avatar');

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->imageService->upload(
                $request->file('avatar'),
                'avatars',
                $user->getRawOriginal('avatar') // old path to delete
            );
        }

        // Reset email verification if email changed
        if ($user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        return $this->userRepository->updateUser($user->id, $data);
    }


    public function destroy($request): void
    {
        $user = $request->user();

        Auth::logout();

        $this->userRepository->destroyUser($user->id);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
