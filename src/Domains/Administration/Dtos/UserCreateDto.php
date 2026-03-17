<?php

namespace Administration\Dtos;

readonly class UserCreateDto
{
    /**
     * User creation DTO constructor.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $roleName
     * @param bool $isActive
     */
    public function __construct(
        private string $name,
        private string $email,
        private string $password,
        private string $roleName,
        private bool $isActive = true,
    ) {}

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the email address.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the role name.
     *
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Convert to array for model creation (DB column names).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->roleName,
            'is_active' => $this->isActive,
        ];
    }
}
