<?php

namespace App\Http\Controllers\Auth;

use Auth\Services\Interfaces\SocialAuthServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

readonly class SocialRedirectController
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
     * Redirect the user to the social provider's authorization page.
     *
     * @param string $provider
     * @return RedirectResponse
     */
    public function __invoke(string $provider): RedirectResponse
    {
        return $this->socialAuthService->redirect($provider);
    }
}
