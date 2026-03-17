<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Administration\Services\Interfaces\UserManagementServiceInterface;

readonly class GetUsersController
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
     * Retrieve all users for the authenticated user's company.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $params = $request->only([
            'page',
            'limit',
            'search',
            'role',
            'is_active',
            'sort_by',
            'sort_order',
        ]);

        $users = $this->userService->getAllUsers($params);

        return response()->json($users);
    }
}
