<?php
//
namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;
use App\Policies\UserPolicy;

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
        Ticket::class => TicketPolicy::class,
        User::class => UserPolicy::class
    ];
    
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('ticket.update', [TicketPolicy::class, 'update']);
        Gate::define('ticket.delete', [TicketPolicy::class, 'delete']);
        Gate::define('ticket.view', [TicketPolicy::class, 'view']);


        Gate::define('admin-access', function ($user) {
            return $user->is_admin == 10;
        });
        Gate::define('sup-access', function ($user) {
            return $user->is_admin == 5;
        });

        Gate::define('access-inventory', function ($user) {
            return $user->is_admin == 10 || $user->is_admin == 5;
        });

        Gate::define('access-report', function ($user) {
            return $user->is_admin == 10 || $user->is_admin == 5;
        });


    }
}
