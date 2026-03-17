<?php

namespace Administration\Repositories;

use Administration\Dtos\RoleDto;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Spatie's Role is an external model — cannot use BelongsToCompany trait or CompanyScope.
 * Company scoping must be applied manually via where('company_id', ...) in each query.
 */
class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles for the current company with permissions.
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Role::where('company_id', getPermissionsTeamId())
            ->with('permissions:id,name')
            ->get();
    }

    /**
     * Find a role by ID within the current company or throw 404.
     *
     * @param int $roleId
     * @return Role
     */
    public function findOrFail(int $roleId): Role
    {
        return Role::where('company_id', getPermissionsTeamId())
            ->findOrFail($roleId);
    }

    /**
     * Create a new role with permissions in the current company.
     *
     * @param RoleDto $dto
     * @return Role
     */
    public function create(RoleDto $dto): Role
    {
        $role = Role::create([
            'name' => $dto->getName(),
            'guard_name' => 'api',
            'company_id' => getPermissionsTeamId(),
        ]);

        $role->syncPermissions($dto->getPermissions());

        return $role->load('permissions:id,name');
    }

    /**
     * Update a role name and sync permissions.
     *
     * @param Role $role
     * @param RoleDto $dto
     * @return Role
     */
    public function update(Role $role, RoleDto $dto): Role
    {
        $role->update(['name' => $dto->getName()]);
        $role->syncPermissions($dto->getPermissions());

        return $role->load('permissions:id,name');
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
