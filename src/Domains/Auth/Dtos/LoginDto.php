<?php

namespace Auth\Dtos;

readonly class LoginDto
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        private string $email,
        private string $password
    )
    {
        //
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
