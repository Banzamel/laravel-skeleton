<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\JsonResponse;
use Administration\Services\Interfaces\RoleServiceInterface;

readonly class DeleteRoleController
{
    /**
     * Create a new DeleteRoleController instance.
     *
     * @param \Administration\Services\Interfaces\RoleServiceInterface $roleService
     */
    public function __construct(private RoleServiceInterface $roleService)
    {
    }

    /**
     * Delete the specified role by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->roleService->deleteRole($id);

        return response()->json(null, 204);
    }
}
