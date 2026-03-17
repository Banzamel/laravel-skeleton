<?php

namespace Administration\Services;

use Administration\Dtos\RoleDto;
use Administration\Events\RoleCreatedEvent;
use Administration\Events\RoleUpdatedEvent;
use Administration\Events\RoleDeletedEvent;
use Administration\Repositories\RoleRepositoryInterface;
use Administration\Services\Interfaces\RoleServiceInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

readonly class RoleService implements RoleServiceInterface
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * Get all company roles with permissions.
     *
     * @return Collection
     */
    public function getAllRoles(): Collection
    {
        return $this->roleRepository->findAll();
    }

    /**
     * Create a new role in the company with permission assignment.
     *
     * @param RoleDto $dto
     * @return Role
     */
    public function createRole(RoleDto $dto): Role
    {
        $role = $this->roleRepository->create($dto);

        event(new RoleCreatedEvent($role, auth()->user()));

        return $role;
    }

    /**
     * Update a role in the company with permission sync.
     *
     * @param int $id
     * @param RoleDto $dto
     * @return Role
     */
    public function updateRole(int $id, RoleDto $dto): Role
    {
        $role = $this->roleRepository->findOrFail($id);
        $role = $this->roleRepository->update($role, $dto);

        event(new RoleUpdatedEvent($role, auth()->user()));

        return $role;
    }

    /**
     * Delete a role from the company.
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        $role = $this->roleRepository->findOrFail($id);

        event(new RoleDeletedEvent($role, auth()->user()));

        return $this->roleRepository->delete($role);
    }
}
