<?php

namespace App\Providers;

use App\Models\User;
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
        // Load UI helper functions
        if (file_exists(app_path('Helpers/UIHelper.php'))) {
            require_once app_path('Helpers/UIHelper.php');
        }

        // Admin has full access for any ability.
        Gate::before(function (User $user) {
            return $user->isAdmin() ? true : null;
        });

        // Register each permission slug as a Gate ability.
        foreach (config('permissions.permissions', []) as $permission) {
            $ability = $permission['slug'];
            Gate::define($ability, function (User $user) use ($ability) {
                return $user->hasPermission($ability);
            });
        }

        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
