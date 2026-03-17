<?php

namespace Administration\Services\Interfaces;

interface PermissionServiceInterface
{
    /**
     * Get all permissions grouped by module.
     *
     * @return array
     */
    public function getAllPermissions(): array;
}
