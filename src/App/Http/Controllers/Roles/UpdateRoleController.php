<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\JsonResponse;
use Administration\Requests\RoleRequest;
use Administration\Services\Interfaces\RoleServiceInterface;

readonly class UpdateRoleController
{
    /**
     * Create a new UpdateRoleController instance.
     *
     * @param \Administration\Services\Interfaces\RoleServiceInterface $roleService
     */
    public function __construct(private RoleServiceInterface $roleService)
    {
    }

    /**
     * Update the specified role by ID.
     *
     * @param \Administration\Requests\RoleRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->getDto());

        return response()->json($role);
    }
}
