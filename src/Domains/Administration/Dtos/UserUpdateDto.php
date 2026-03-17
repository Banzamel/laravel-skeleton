<?php

namespace Administration\Dtos;

readonly class UserUpdateDto
{
    /**
     * User update DTO constructor.
     *
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $roleName
     * @param bool|null $isActive
     */
    public function __construct(
        private ?string $name = null,
        private ?string $email = null,
        private ?string $password = null,
        private ?string $roleName = null,
        private ?bool $isActive = null,
    ) {}

    /**
     * Get the user's name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the email address.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get the role name.
     *
     * @return string|null
     */
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    /**
     * Check if the user is active.
     *
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Convert DTO to array, excluding null values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => $this->isActive,
        ], fn($value) => $value !== null);
    }
}
