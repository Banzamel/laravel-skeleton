<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\JsonResponse;
use Administration\Services\Interfaces\RoleServiceInterface;

readonly class GetRolesController
{
    /**
     * Create a new GetRolesController instance.
     *
     * @param \Administration\Services\Interfaces\RoleServiceInterface $roleService
     */
    public function __construct(private RoleServiceInterface $roleService)
    {
    }

    /**
     * Retrieve all available roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $roles = $this->roleService->getAllRoles();

        return response()->json($roles);
    }
}
