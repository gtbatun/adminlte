<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;

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
        // Ticket::class => TicketPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('ticket.update', [TicketPolicy::class, 'update']);
        Gate::define('ticket.delete', [TicketPolicy::class, 'delete']);

        Gate::define('view-all-tickets', function ($user) {
            return $user->isAdmin();
        });
        Gate::define('view-own-tickets', function ($user, Ticket $ticket) {
            return $user->id === $ticket->user_id;
        });
    }
}
