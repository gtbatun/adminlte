<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        
        // return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
       // todos solo pueden ver los tickets que ellos mismos crearon
        //return $user->id == $ticket->user_id || $user->is_admin === 10; 
        //
        
        if ($user->is_admin == 10) {
            return true; // El administrador puede ver todos los tickets
        }
    
        // Verificar si el ticket pertenece al usuario
        if ($user->id === $ticket->user_id) {
            return true;
        }
    
        // Verificar si el ticket pertenece a un departamento del usuario
        // Aquí deberás adaptar la lógica según cómo tengas implementado el sistema de departamentos
        if ($user->department_id === $ticket->department_id) {
            return true;
        }
    
        return false; // Si no cumple ninguna de las condiciones anteriores, no puede ver el ticket
    
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->is_admin == 10) {
            return true; // El administrador puede ver todos los tickets
        }
        return false; // Si no cumple ninguna de las condiciones anteriores, no puede ver el ticket
        // return $user->id === $ticket->user_id || $user->department_id === $ticket->department_id;
        // return $user->is_admin === 1;
        // return $user->is($ticket->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // return $user->id === $ticket->user_id;
        return $user->is_admin === 10;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        //
    }
}
