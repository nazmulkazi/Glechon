<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // User action authorization
        Gate::before(function ($user, $ability) {
            // Administrators are allowed to perform any actions.
            list($action, $domain) = preg_split('/[\-\.]/', $ability);
            $permissions = $user->permissions->$domain ?? [];
            return $user->role === 'admin' || ($action === 'any' && count($permissions) > 0) || in_array($action, $permissions);
        });
    }
}
