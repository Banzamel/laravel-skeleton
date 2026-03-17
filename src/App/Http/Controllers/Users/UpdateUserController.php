<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Administration\Requests\UserUpdateRequest;
use Administration\Services\Interfaces\UserManagementServiceInterface;

readonly class UpdateUserController
{
    /**
     * Initialize the controller with the user management service.
     *
     * @param \Administration\Services\Interfaces\UserManagementServiceInterface $userService
     */
    public function __construct(private UserManagementServiceInterface $userService)
    {
    }

    /**
     * Update the specified user.
     *
     * @param \Administration\Requests\UserUpdateRequest $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UserUpdateRequest $request, int $userId): JsonResponse
    {
        $user = $this->userService->updateUser($userId, $request->getDto());

        return response()->json($user);
    }
}
