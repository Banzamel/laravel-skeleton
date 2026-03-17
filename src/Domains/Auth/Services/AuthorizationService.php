<?php

namespace Auth\Services;

use Auth\Dtos\LoginDto;
use Auth\Dtos\RefreshLoginDto;
use Auth\Services\Interfaces\AuthorizationServiceInterface;
use Illuminate\Http\Request as HttpRequest;
use Laravel\Passport\Client;
use Shared\Exceptions\ApiJsonException;

readonly class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * @param LoginDto $loginDto
     * @param string $client
     * @return array
     * @throws ApiJsonException
     */
    public function login(LoginDto $loginDto, string $client): array
    {
        $client = Client::where('name', $client)->first();

        if (!$client) {
            throw new ApiJsonException('Client not found', 404);
        }

        $user = \Auth\Models\User::where('email', $loginDto->getEmail())->first();

        if (!$user) {
            throw new ApiJsonException('User not found', 404);
        }

        $response = $this->requestToken([
            'grant_type' => 'password',
            'client_id' => $client->id,
            'username' => $loginDto->getEmail(),
            'password' => $loginDto->getPassword(),
            'scope' => 'api',
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new ApiJsonException('Invalid credentials', 401);
        }

        event(new \Auth\Events\LoginEvent($user));

        $data = json_decode($response->getContent(), true);

        // Set Spatie team context for roles/permissions
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
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'token_type' => 'Bearer',
            'expires_in' => $data['expires_in'],
        ];
    }

    /**
     * @param RefreshLoginDto $refreshDto
     * @param string $client
     * @return array
     * @throws ApiJsonException
     */
    public function refresh(RefreshLoginDto $refreshDto, string $client): array
    {
        $client = Client::where('name', $client)->first();

        if (!$client) {
            throw new ApiJsonException('Client not found', 404);
        }

        $response = $this->requestToken([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshDto->getRefreshToken(),
            'client_id' => $client->id,
            'scope' => 'api',
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new ApiJsonException('Invalid refresh token', 401);
        }

        $data = json_decode($response->getContent(), true);

        return [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'token_type' => 'Bearer',
            'expires_in' => $data['expires_in'],
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws ApiJsonException
     */
    public function logout(\Illuminate\Http\Request $request): array
    {
        try {
            if ($request->user()) {
                $request->user()->token()->revoke();
                event(new \Auth\Events\LogoutEvent($request->user()));
            }

            return ['message' => 'logged_out'];
        } catch (\Exception $e) {
            throw new ApiJsonException($e->getMessage(), 500);
        }
    }

    /**
     * Make an internal subrequest to Passport's /oauth/token endpoint.
     *
     * @param array $params
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    private function requestToken(array $params): \Symfony\Component\HttpFoundation\Response
    {
        $request = HttpRequest::create('/oauth/token', 'POST', $params);

        return app()->handle($request);
    }
}
