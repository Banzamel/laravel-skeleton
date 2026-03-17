<?php

namespace Administration\Services\Interfaces;

use Administration\Dtos\RoleDto;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleServiceInterface
{
    /**
     * Get all company roles with permissions.
     *
     * @return Collection
     */
    public function getAllRoles(): Collection;

    /**
     * Create a new role in the company.
     *
     * @param RoleDto $dto
     * @return Role
     */
    public function createRole(RoleDto $dto): Role;

    /**
     * Update a role in the company.
     *
     * @param int $id
     * @param RoleDto $dto
     * @return Role
     */
    public function updateRole(int $id, RoleDto $dto): Role;

    /**
     * Delete a role from the company.
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool;
}
