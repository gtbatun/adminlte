// Caso desde la notificación, solo tenemos el ticketId
        // Realizar la llamada AJAX para marcar como leídas las notificaciones relacionadas con el mismo ticket_id

            // $.ajax({
            //     url: '/tickets/' + ticketId + '/details',
            //     method: 'GET',
            //     success: function(ticket) {
            //         // Asignar los datos al modal

            //         $('#modal-gestion-ticket').find('#ticket-id').val(ticket.id);            
            //         $('#modal-gestion-ticket').find('#ticket-name-title').text(ticket.title);
            //         $('#modal-gestion-ticket').find('#ticket-description').text(ticket.description);
            //         handleTicketStatus(ticket.status_id, ticket.department_id);
            //         // Mostrar el modal
            //         $('#modal-gestion-ticket').modal('show');
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('Error fetching ticket details:', error);
            //     }
            // });


/** -------------------------------------------------------- */

public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $user = User::find($userId); // Cargamos explícitamente al usuario desde la base de datos
            $unreadNotifications = $user->unreadNotifications()->get(); // Obtenemos las 3 notificaciones más recientes
            $unreadNotificationsCount = $user->unreadNotifications()->count(); // Contamos todas las notificaciones no leídas

            if ($unreadNotificationsCount > 0) {
            // Inicializamos el arreglo de submenús
            $notificationSubmenu = [];

            foreach ($unreadNotifications as $notification) {
                $notificationSubmenu[] = [
                    'text' => $notification->data['message'],
                    'url' => '#',
                    'icon' => 'fas fa-fw fa-bell',
                    'data' => [
                        'ticket-id' => $notification->data['ticket_id'] // Asumiendo que tienes un ticket_id en la notificación
                    ],
                    'classes' => 'notification-btn',
                    
                ];
            }

            config([
                'adminlte.menu' => array_merge(config('adminlte.menu'), [
                    [                        
                    'text' => $unreadNotificationsCount,
                    'classes' => 'text-danger',
                    'icon' => 'far fa-bell',
                    'topnav_right' => true,
                    'icon_color' => 'green', 
                    'url' => '#',
                    'submenu' => $notificationSubmenu,  
                    ]
                ])
            ]);
            }
        }   

        return $next($request);
    }