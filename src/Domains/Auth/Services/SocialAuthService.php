<?php

namespace Auth\Services;

use Auth\Models\SocialAccount;
use Auth\Models\User;
use Auth\Services\Interfaces\SocialAuthServiceInterface;
use Laravel\Passport\Client;
use Laravel\Socialite\Facades\Socialite;
use Shared\Exceptions\ApiJsonException;
use Symfony\Component\HttpFoundation\RedirectResponse;

readonly class SocialAuthService implements SocialAuthServiceInterface
{
    /**
     * Supported social providers.
     */
    private const array SUPPORTED_PROVIDERS = ['google', 'facebook'];

    /**
     * Get the redirect URL for the given social provider.
     *
     * @param string $provider
     * @return RedirectResponse
     * @throws ApiJsonException
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Handle the callback from the social provider and return auth tokens.
     *
     * @param string $provider
     * @return array
     * @throws ApiJsonException
     */
    public function callback(string $provider): array
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            throw new ApiJsonException('Failed to authenticate with ' . $provider, 401);
        }

        $user = $this->findUserBySocialAccount($provider, $socialUser->getId())
            ?? $this->findAndLinkUser($provider, $socialUser);

        if (!$user) {
            throw new ApiJsonException('No account found for this email. Contact your administrator.', 404);
        }

        if (!$user->is_active) {
            throw new ApiJsonException('Account is deactivated', 403);
        }

        return $this->issueToken($user);
    }

    /**
     * Find a user by their social account link.
     *
     * @param string $provider
     * @param string $providerId
     * @return User|null
     */
    private function findUserBySocialAccount(string $provider, string $providerId): ?User
    {
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        return $socialAccount?->user;
    }

    /**
     * Find user by email and link the social account.
     *
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @return User|null
     */
    private function findAndLinkUser(string $provider, \Laravel\Socialite\Contracts\User $socialUser): ?User
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            return null;
        }

        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ]);

        return $user;
    }

    /**
     * Issue a Passport personal access token for the user.
     *
     * @param User $user
     * @return array
     * @throws ApiJsonException
     */
    private function issueToken(User $user): array
    {
        $tokenResult = $user->createToken('Social Login', ['api']);

        event(new \Auth\Events\LoginEvent($user));

        setPermissionsTeamId($user->company_id);

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'is_active' => $user->is_active,
                'avatar_url' => $user->avatar_url,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Validate that the provider is supported.
     *
     * @param string $provider
     * @throws ApiJsonException
     */
    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, self::SUPPORTED_PROVIDERS)) {
            throw new ApiJsonException('Unsupported social provider: ' . $provider, 422);
        }
    }
}
