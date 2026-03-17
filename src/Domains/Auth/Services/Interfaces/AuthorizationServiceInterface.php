<?php

namespace Auth\Services\Interfaces;

use Auth\Dtos\LoginDto;
use Auth\Dtos\RefreshLoginDto;

interface AuthorizationServiceInterface
{
    /**
     * Authenticate a user and return token data.
     *
     * @param \Auth\Dtos\LoginDto $loginDto
     * @param string $client
     * @return array
     */
    public function login(LoginDto $loginDto, string $client): array;

    /**
     * Refresh an access token using a refresh token.
     *
     * @param \Auth\Dtos\RefreshLoginDto $refreshDto
     * @param string $client
     * @return array
     */
    public function refresh(RefreshLoginDto $refreshDto, string $client): array;

    /**
     * Revoke the current user's access token and log out.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function logout(\Illuminate\Http\Request $request): array;
}
