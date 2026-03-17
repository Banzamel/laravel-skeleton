<?php

namespace App\Http\Controllers\Auth;

use Auth\Services\Interfaces\SocialAuthServiceInterface;
use Illuminate\Http\JsonResponse;

readonly class SocialCallbackController
{
    /**
     * Initialize the controller with the social auth service.
     *
     * @param SocialAuthServiceInterface $socialAuthService
     */
    public function __construct(private SocialAuthServiceInterface $socialAuthService)
    {
    }

    /**
     * Handle the callback from the social provider.
     *
     * @param string $provider
     * @return JsonResponse
     */
    public function __invoke(string $provider): JsonResponse
    {
        return response()->json($this->socialAuthService->callback($provider));
    }
}
