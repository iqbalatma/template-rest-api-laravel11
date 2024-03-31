<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(Role::SUPERADMIN->value) ? true : null;
        });

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return config("app.fe_url") . "/forgot-password/reset/$user->email/$token";
        });
    }
}
