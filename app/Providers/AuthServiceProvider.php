<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin',function($user){
            return $user->role_id === 1;
        });

        Gate::define('cf_manager-higher',function($user){
            return $user->role_id > 0 && $user->role_id <= 3;
        });

        Gate::define('manager-higher',function($user){
            return $user->role_id > 0 && $user->role_id <= 5;
        });

        Gate::define('staff-higher',function($user){
            return $user->role_id > 0 && $user->role_id <= 7;
        });

        Gate::define('user-higher',function($user){
            return $user->role_id > 0 &&  $user->role_id <=9;
        });
    }
}
