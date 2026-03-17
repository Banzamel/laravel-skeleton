<?php

namespace Administration\Dtos;

readonly class RoleDto
{
    /**
     * Role DTO constructor.
     *
     * @param string $name
     * @param array $permissions
     */
    public function __construct(
        private string $name,
        private array $permissions,
    ) {}

    /**
     * Get the role name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the role's permissions list.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
