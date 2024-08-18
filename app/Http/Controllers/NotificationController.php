<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

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

    public function markAsRead11($id)
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

    public function markAsRead(Request $request)
    {
        
        
        $ticketId = $request->input('ticket_id');
        
        // Obtener todas las notificaciones no leídas del usuario actual que tienen el mismo ticket_id
        // $user = Auth::user();
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $notifications = $user->unreadNotifications()->where('data->ticket_id', $ticketId)->get();

        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No se encontraron notificaciones para el ticket especificado.']);
        }
        

        // Marcar cada notificación como leída
        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $unreadNotificationsCount = $user->unreadNotifications()->count();
        session(['unread_notifications_count' => $unreadNotificationsCount]);

        return response()->json(['message' => 'Notificaciones marcadas como leídas.']);
    }
    public function getUnreadNotificationsCount()
    {
        $unreadNotificationsCount = session('unread_notifications_count', 0);
        return response()->json(['unread_notifications_count' => $unreadNotificationsCount]);
    }


}
