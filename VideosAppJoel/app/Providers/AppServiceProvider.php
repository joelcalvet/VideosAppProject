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

    protected $policies = [
        // Ho deixo buit perquè m'has dit que tenia que ser així
    ];

    function define_gates()
    {
        Gate::define('manage-videos', function (User $user) {
            return $user->hasPermissionTo('manage videos') || $user->isSuperAdmin();
        });
        Gate::define('manage-users', function (User $user) {
            return $user->hasPermissionTo('manage users') || $user->isSuperAdmin();
        });
    }

    protected function registerPolicies()
    {
        // Recorrem el array de polítiques i les registrem a través de Gate.
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->define_gates();
    }
}
