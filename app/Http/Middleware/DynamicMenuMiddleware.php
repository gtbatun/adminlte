<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class DynamicMenuMiddleware
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $user = User::find($userId); // Cargamos explícitamente al usuario desde la base de datos
            $unreadNotifications = $user->unreadNotifications()->get(); // Obtenemos las 3 notificaciones más recientes
            $unreadNotificationsCount = $user->unreadNotifications()->count(); // Contamos todas las notificaciones no leídas
            // Agrega el contador a la sesión o compártelo con las vistas
            session(['unread_notifications_count' => $unreadNotificationsCount]);

            if ($unreadNotificationsCount > 0) {
            // Inicializamos el arreglo de submenús
            $notificationSubmenu = [];

            foreach ($unreadNotifications as $notification) {
                if (isset($notification->data['ticket_id'], $notification->data['message'])) {
                    $notificationSubmenu[] = [
                        // 'text' => $notification->data['ticket_id'],$notification->data['message'],
                        'text' => $notification->data['ticket_id'] . ' - ' . $notification->data['message'],
                        'url' => '#',
                        'icon' => 'fas fa-fw fa-bell',
                        'data' => [
                            'ticket-id' => $notification->data['ticket_id'] // Asumiendo que tienes un ticket_id en la notificación
                        ],
                        'classes' => 'notification-btn',                        
                    ];
                }
            }

            config([
                'adminlte.menu' => array_merge(config('adminlte.menu'), [
                    [      
                    'text' => $unreadNotificationsCount,                  
                    // 'text' => $unread,
                    'classes' => 'text-danger',
                    'icon' => 'far fa-bell',
                    'topnav_right' => true,
                    'icon_color' => 'green', 
                    'url' => '#',
                    'submenu' => $notificationSubmenu,  
                    'id' => 'contadorNotificacion'
                    ]
                ])
            ]);
            }
        }   

        return $next($request);
    }
}
