<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(env('TOKENS_EXPIRE_IN', 15)));

        Passport::refreshTokensExpireIn(now()->addDays(env('REFRESH_TOKENS_EXPIRE_IN', 30)));

        Passport::personalAccessTokensExpireIn(now()->addMonths(env('PERSONAL_TOKENS_EXPIRE_IN', 6)));
    }
}
