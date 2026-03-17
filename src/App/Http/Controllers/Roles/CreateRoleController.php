<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\JsonResponse;
use Administration\Requests\RoleRequest;
use Administration\Services\Interfaces\RoleServiceInterface;

readonly class CreateRoleController
{
    /**
     * Create a new CreateRoleController instance.
     *
     * @param \Administration\Services\Interfaces\RoleServiceInterface $roleService
     */
    public function __construct(private RoleServiceInterface $roleService)
    {
    }

    /**
     * Store a newly created role.
     *
     * @param \Administration\Requests\RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->getDto());

        return response()->json($role, 201);
    }
}
