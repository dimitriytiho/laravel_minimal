<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    // ВНИМАНИЕ! Название ролей должны быть: admin, editor, user
    /**
     *
     * @var string
     * Имя роли Admin.
     */
    public const ROLE_ADMIN = 'admin';
    /**
     *
     * @var string
     * Имя роли User.
     */
    public const ROLE_USER = 'user';
    /**
     *
     * @var array
     * Имена ролей с доступом в admin панель, перечислить роли в массиве.
     */
    public const ROLES_ADMIN_PANEL = ['admin', 'editor'];



    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant 'admin' role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole(self::ROLE_ADMIN) ? true : null;
        });
    }
}
