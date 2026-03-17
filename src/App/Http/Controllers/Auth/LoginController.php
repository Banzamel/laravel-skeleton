<?php

namespace App\Http\Controllers\Auth;

use Auth\Requests\LoginRequest;
use Auth\Services\Interfaces\AuthorizationServiceInterface;
use Illuminate\Http\JsonResponse;

readonly class LoginController
{
    /**
     * Initialize the controller with the authorization service.
     *
     * @param AuthorizationServiceInterface $authService
     */
    public function __construct(private AuthorizationServiceInterface $authService)
    {
    }

    /**
     * Log in the user and return OAuth tokens.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        return response()->json(
            $this->authService->login($request->getDto(), config('passport.clients.desktop'))
        );
    }
}
