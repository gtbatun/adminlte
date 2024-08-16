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
            // $unreadNotificationsCount = $user->unreadNotifications()->count(); // recien agregado
            $unreadNotifications = $user->unreadNotifications()->get(); // Obtenemos las 3 notificaciones más recientes
            $unreadNotificationsCount = $user->unreadNotifications()->count(); // Contamos todas las notificaciones no leídas

            if ($unreadNotificationsCount > 0) {
            // Inicializamos el arreglo de submenús
            $notificationSubmenu = [];

            foreach ($unreadNotifications as $notification) {
                $notificationSubmenu[] = [
                    'text' => $notification->data['message'], // Asumiendo que las notificaciones tienen un campo 'message'
                     // Cambia esto por la URL real de la notificación
                    //'url' => $notification->data['url'], // Usamos la URL almacenada en la notificación
                    'url' => '#',
                    'icon' => 'fas fa-fw fa-bell',
                    'data' => [
                        'ticket-id' => $notification->data['ticket_id'] // Asumiendo que tienes un ticket_id en la notificación
                    ],
                    'classes' => 'notification-btn',
                    
                ];
            }

            // Agregamos la opción de ver todas las notificaciones
            // $notificationSubmenu[] = [
            //     'text' => 'See All Notifications',
            //     'url' => route('notifications.index'), // Cambia esto por la ruta real a todas las notificaciones
            //     'icon' => 'fas fa-fw fa-bell',
            // ];


            config([
                'adminlte.menu' => array_merge(config('adminlte.menu'), [
                    [                        
                    'text' => $unreadNotificationsCount,
                    'classes' => 'text-danger',
                    'icon' => 'far fa-bell',
                    'topnav_right' => true,
                    'icon_color' => 'green', 
                    'url' => '#',
                    // 'route' => ['user.edit', ['user' => $userId]],
                    'submenu' => $notificationSubmenu,  
                    ]
                ])
            ]);
            }
        }
   

        return $next($request);
    }
}
