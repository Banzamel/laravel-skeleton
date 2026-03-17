<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Administration\Requests\UserCreateRequest;
use Administration\Services\Interfaces\UserManagementServiceInterface;

readonly class CreateUserController
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
     * Store a newly created user.
     *
     * @param \Administration\Requests\UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UserCreateRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->getDto());

        return response()->json($user, 201);
    }
}
