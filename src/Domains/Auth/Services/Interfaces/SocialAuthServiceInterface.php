<?php

namespace Auth\Services\Interfaces;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface SocialAuthServiceInterface
{
    /**
     * Get the redirect URL for the given social provider.
     *
     * @param string $provider
     * @return RedirectResponse
     */
    public function redirect(string $provider): RedirectResponse;

    /**
     * Handle the callback from the social provider and return auth tokens.
     *
     * @param string $provider
     * @return array
     */
    public function callback(string $provider): array;
}
