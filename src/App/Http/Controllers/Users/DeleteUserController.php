<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Administration\Services\Interfaces\UserManagementServiceInterface;

readonly class DeleteUserController
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
     * Delete the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, int $userId): JsonResponse
    {
        $bool = $this->userService->deleteUser($userId);

        return response()->json(['deleted' => $bool], 204);
    }
}
