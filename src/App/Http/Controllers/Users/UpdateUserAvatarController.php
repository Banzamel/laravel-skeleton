<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Administration\Requests\UserAvatarRequest;
use Administration\Services\Interfaces\UserManagementServiceInterface;

readonly class UpdateUserAvatarController
{
    /**
     * @param UserManagementServiceInterface $userService
     */
    public function __construct(private UserManagementServiceInterface $userService)
    {
    }

    /**
     * Upload and update a user's avatar image.
     *
     * @param \Administration\Requests\UserAvatarRequest $request
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UserAvatarRequest $request, int $user): JsonResponse
    {
        $avatar = $request->file('avatar');
        $updatedUser = $this->userService->updateUserAvatar($user, $avatar);

        return response()->json($updatedUser);
    }
}
