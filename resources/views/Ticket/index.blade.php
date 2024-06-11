@extends('adminlte::page')

@section('content')

<script src="{{asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/toastr.min.js')}}"></script>
<!-- Incluye Toastr si deseas notificaciones visuales -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">



<div class="row">
    <div class="col-12 mt-0 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>         
        @else
            <h3>@lang('Tickets')</h3>
        @endisset
        <a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>        
    </div>
    @if(Session::get('success'))
    <div class="container-fluid">
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    </div>
    @endif
    @include('partials.validation-errors')

    <div class="col-12 mt-1">
        <div class="card fluid">   
            <div class="card-body">  
                <div class="table-responsive">
                    <table id="tickets-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>ID</th>
                                <th>TICKET</th>
                                <th>CATEGORIA</th>
                                <th>ASIGNADO</th>
                                <th>SUCURSAL</th>
                                <!-- <th>AREA</th> -->
                                <th>ESTATUS</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<button id="enable-sound-notifications" class="btn btn-warning">Habilitar notificaciones de sonido</button>


<!---------------------------------- Modal de reasignar ticket  ---------------------->
<div class="modal" id="modal-reasig-ticket">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-titulo">Reasignar ticket<strong class="text-danger"><span id="ticket-name-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <input type="text" name="ticket_id" id="ticket-id">
                    <div class="form-group">
                        <label for="Departamento">Nuevo Departamento</label>
                        <input type="text" name="department_id" id="department_id" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        var table;
        let audio = new Audio('/storage/images/user/notification-sound.mp3');

        function loadTickets() {
            table = $('#tickets-table').DataTable({
                "order": [[0, "desc"]],
                "language": {
                    "search": "Buscar",
                    "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                    "info": "Mostrando _START_ de _END_ de _TOTAL_ ",
                    "infoFiltered": "(filtrado de un total de _MAX_)",
                    "emptyTable": "Sin Datos a Mostrar",
                    "zeroRecords": "No se encontraron coincidencias",
                    "infoEmpty": "Mostrando 0 de 0 de 0 coincidencias",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Ultimo"
                    },
                },
                ajax: {
                    url: "{{ route('tickets.data') }}",
                    dataSrc: 'data',
                    error: function(xhr, status, error) {
                        if (xhr.status === 401 || xhr.status === 419) {
                            window.location.href = '{{ route("login") }}';
                        } else {
                            console.log("Ajax error: " + error);
                        }
                    }
                },
                columns: [
                    { data: 'id', render: function(data, type, row, meta) {
                        return row.status === 'Nuevo' ? 
                            '<span style="color:orange" class="pending-id">' + data + '</span>' : 
                            '<span style="color:green" class="default-id">' + data + '</span>';
                    }},
                    { data: 'title' },
                    { data: 'category' },
                    { data: 'type' },
                    { data: 'sucursal' },
                    // { data: 'area' },
                    { data: 'status' },
                    { data: 'actions', orderable: false, searchable: false}
                ],
                createdRow: function(row, data, dataIndex) {
                    $('td', row).eq(3).css('background-color', data.typeColor);
                    $('td', row).eq(5).css('background-color', data.typeColorback);
                },
                responsive: true,
                paging: true
            });
        }

        $('#tickets-table').on('error.dt', function(e, settings, techNote, message) {
            console.log('DataTables error: ', message);
        });

        loadTickets();

        function getReloadInterval() {
            var now = new Date();
            var hours = now.getHours();
            return (hours >= 8 && hours < 17) ? 60000 : 1800000;
        }

        function setReloadInterval() {
            var interval = getReloadInterval();
            setInterval(function() {
                if (table) {
                    table.ajax.reload(null, false);
                }
            }, interval);
        }
        setReloadInterval();

        /** Script para notificar los cambio en los tickets con sonido */
        let lastUpdateTime = null;

        // Verificar si el usuario ya ha habilitado las notificaciones de sonido
        if (localStorage.getItem('soundNotificationsEnabled') === 'true') {
            $('#enable-sound-notifications').hide();
            // setInterval(checkForUpdates, 5000);
        }

        $('#enable-sound-notifications').on('click', function() {
            audio.play().then(() => {
                audio.pause();
                audio.currentTime = 0;
                $('#enable-sound-notifications').hide();
                localStorage.setItem('soundNotificationsEnabled', 'true');
                // setInterval(checkForUpdates, 5000);
            }).catch(function(error) {
                console.error('Error al iniciar el audio:', error);
            });
        });

        function checkForUpdates() {
            $.ajax({
                url: "{{ route('tickets.check-updates') }}",
                method: "GET",
                success: function(data) {
                    if (lastUpdateTime !== null && lastUpdateTime !== data.last_updated_at) {
                        sonido();
                        alert('Función de captura de pantalla no implementada');
                    }
                    lastUpdateTime = data.last_updated_at;
                },
                error: function() {
                    console.error("Error al verificar actualizaciones");
                }
            });
        }

        function sonido() {
            audio.play().catch(function(error) {
                console.error('Error al reproducir el sonido de notificación:', error);
            });
        }        
        /** Scrip para habilitar las funciones del boton modal para la reasignacion del ticket */
        // Manejar el clic en el botón de reasignar
        $(document).on('click', '.modal-reasig-btn', function() {
            var ticketId = $(this).data('ticket-id');
            var ticketTitle = $(this).data('ticket-title');

            $('#modal-reasig-ticket').find('#ticket-id').val(ticketId);
            $('#modal-reasig-ticket').find('#ticket-name-title').text(ticketTitle);
        });
        /**Fin de script */
    });
</script>
@endsection
