<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\EnsureLoginIsNotThrottled;
use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::authenticateThrough(function () {
            return array_filter([
                config('fortify.limiters.login') ? null : \App\Actions\Fortify\EnsureLoginIsNotThrottled::class,
                \App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable::class,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]);
        });
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions([
            'note:create', 'note:read', 'note:update', 'note:delete',
            'comment:create', 'comment:read', 'comment:update', 'comment:delete',
            'user:read',
        ]);

        Jetstream::permissions([
            'note:create', 'note:read', 'note:update', 'note:delete',
            'comment:create', 'comment:read', 'comment:update', 'comment:delete',
            'user:create', 'user:read', 'user:update', 'user:delete',
        ]);
    }
}
