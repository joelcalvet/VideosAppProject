<?php

namespace App\Providers;

use App\Models\Serie;
use App\Models\User;
use App\Policies\SeriesPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;

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
        Serie::class => SeriesPolicy::class,
    ];

    function define_gates()
    {
        Gate::define('manage-videos', function (User $user) {
            return $user->hasPermissionTo('manage videos') || $user->isSuperAdmin();
        });
        Gate::define('manage-users', function (User $user) {
            return $user->hasPermissionTo('manage users') || $user->isSuperAdmin();
        });
        Gate::define('manage-series', function (User $user) {
            return $user->hasPermissionTo('manage series') || $user->isSuperAdmin();
        });
        Gate::define('create-videos', function (User $user) {
            return $user->hasPermissionTo('create videos') || $user->isSuperAdmin();
        });
        Gate::define('view-notification', function (User $user, DatabaseNotification $notification) {
            return $notification->notifiable_id === $user->id;
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
