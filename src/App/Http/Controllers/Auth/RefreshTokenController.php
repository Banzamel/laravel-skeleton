<?php

namespace App\Http\Controllers\Auth;

use Auth\Requests\RefreshLoginRequest;
use Auth\Services\Interfaces\AuthorizationServiceInterface;
use Illuminate\Http\JsonResponse;

readonly class RefreshTokenController
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
     * Refresh the OAuth access token using a refresh token.
     *
     * @param RefreshLoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(RefreshLoginRequest $request): JsonResponse
    {
        return response()->json(
            $this->authService->refresh($request->getDto(), config('passport.clients.desktop'))
        );
    }
}
