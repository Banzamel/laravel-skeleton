<?php

namespace Administration\Services;

use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

readonly class PermissionService implements Interfaces\PermissionServiceInterface
{
    /**
     * Returns all permissions grouped by module.
     */
    public function getAllPermissions(): array
    {
        $modules = Config::get('permission.modules', []);

        return array_map(function ($permissions) {
            return Permission::whereIn('name', $permissions)
                ->get(['id', 'name'])
                ->toArray();
        }, $modules);
    }
}
