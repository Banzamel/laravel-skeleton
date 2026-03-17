<?php

namespace Administration\Repositories;

use Administration\Dtos\RoleDto;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function findAll(): Collection;
    public function findOrFail(int $roleId): Role;
    public function create(RoleDto $dto): Role;
    public function update(Role $role, RoleDto $dto): Role;
    public function delete(Role $role): bool;
}
