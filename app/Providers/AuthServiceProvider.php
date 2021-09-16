<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\City' => 'App\Policies\CityPolicy',
        'App\Models\Client' => 'App\Policies\ClientPolicy',
        'App\Models\Service' => 'App\Policies\ServicePolicy',
        'App\Models\Income' => 'App\Policies\IncomePolicy',
        'App\Models\Expense' => 'App\Policies\ExpensePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-user', function ($user) {

            if ($user->id === 1){
                return true;
            }

            return false;

        });
    }
}
