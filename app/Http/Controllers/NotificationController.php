<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener todas las notificaciones del usuario
        $notifications = $user->notifications;

        // Obtener solo las notificaciones no leídas
        $unreadNotifications = $user->unreadNotifications;
        // $unreadNotifications = Notification::all();

        // Pasar las notificaciones a la vista
        // return $notifications;
        return view('Inventory.index', compact('notifications', 'unreadNotifications'));
    }

    public function markAsRead($id)
    {
        // Obtener el usuario autenticado
        $user = auth()->user()->department_id;

        // Buscar la notificación por ID y marcarla como leída
        $notification = $user->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        // Redirigir a la lista de notificaciones o donde sea necesario
        return redirect()->route('Ticket.index')->with('success', 'Notificación marcada como leída.');
    }

}
