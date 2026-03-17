<?php

namespace App\Http\Controllers\Auth;

use Auth\Services\Interfaces\AuthorizationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class LogoutController
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
     * Revoke the current user's token and log out.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($this->authService->logout($request));
    }
}
