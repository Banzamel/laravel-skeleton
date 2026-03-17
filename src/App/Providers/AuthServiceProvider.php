<?php

namespace App\Providers;

use Laravel\Passport\Passport;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Ensure consistent morph type for User across Auth and Secretariat domains
        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'user' => \Auth\Models\User::class,
        ]);

        // Enable password grant
        Passport::enablePasswordGrant();

        // Define scopes
        Passport::tokensCan([
            'mobile' => 'Access from mobile app',
            'web' => 'Access from web app',
            'api' => 'Access from external API',
        ]);

        // Token expiration
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
