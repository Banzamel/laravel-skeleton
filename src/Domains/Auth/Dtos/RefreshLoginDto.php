<?php

namespace Auth\Dtos;

readonly class RefreshLoginDto
{
    /**
     * @param string $refreshToken
     */
    public function __construct(
        private string $refreshToken
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
            'refresh_token' => $this->refreshToken
        ];
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
