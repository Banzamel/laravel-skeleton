<?php

namespace App\Http\Controllers\Permissions;

readonly class GetPermissionsController
{
    /**
     * Create a new GetPermissionsController instance.
     *
     * @param \Administration\Services\Interfaces\PermissionServiceInterface $permissionService
     */
    public function __construct(private readonly \Administration\Services\Interfaces\PermissionServiceInterface $permissionService)
    {
        //
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $permissions = $this->permissionService->getAllPermissions();

        return response()->json($permissions);
    }
}
