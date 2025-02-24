
@extends('adminlte::page')
@section('content')
<!-- Incluye jQuery si aún no está incluido -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluye Toastr si deseas notificaciones visuales -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<div class="row" >
    <div class="col-12 mt-0 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>         
        @else
            <h3 >@lang('Tickets')</h3>
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
                <div class="table-responsive ">
                    <table id="tickets-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%" >
                        <thead  class="table-dark ">
                            <tr class="text-center">
                                <th>ID</th>
                                <th>TICKET</th>
                                <th>CATEGORIA</th>
                                <th>ASIGNADO</th>
                                <th>SUCURSAL</th>
                                <th>AREA</th>
                                <th>ESTATUS</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>

    document.addEventListener('DOMContentLoaded',function(){
        var table;
        function loadTickets(){
        table = $('#tickets-table').DataTable({
            
            "order": [[ 0,"desc" ]],
            "language": {
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                "info":"Mostrando _START_ de _END_ de _TOTAL_ ",
                "infoFiltered":   "( filtrado de un total de _MAX_)",
                "emptyTable":     "Sin Datos a Mostrar",
                "zeroRecords":    "No se encontraron coincidencias",
                "infoEmpty":      "Mostrando 0 de 0 de 0 coincidencias",
                "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Ultimo",
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
                // { data: 'id' },
                { data: 'id', render: function (data, type, row, meta) {
                    if (row.status === 'Nuevo') {
                        return '<span style="color:orange" class="pending-id">' + data + '</span>';
                    } else {
                        return '<span style="color:green" class="default-id">' + data + '</span>';
                    }
                }},
                { data: 'title' },
                { data: 'category' },
                { data: 'type' },
                { data: 'sucursal' },
                { data: 'area' },
                { data: 'status' },
                { data: 'actions', orderable: false, searchable: false }
            ],
            createdRow: function(row, data, dataIndex) {
            // Aplica el color basado en el valor de 'typeColor'
            $('td', row).eq(3).css('background-color', data.typeColor); // 'eq(5)' es el índice de la columna 'type'
            $('td', row).eq(6).css('background-color', data.typeColorback); // 'eq(5)' es el índice de la columna 'type'
            //$(row).css('background-color', data.typeColorback); // 'eq(5)' es el índice de la columna 'type'

            //var typeCell = $('td', row).eq(4); // 'eq(5)' es el índice de la columna 'type'
            var typeColorback = (data.type === 'Asignado') ? 'rgba(0, 0, 255)' : 'rgba(0, 255, 0, 0.2)';
            },
            responsive: true,
            paging: true, // Enable pagination
                
        });
    }

    $('#tickets-table').on('error.dt', function(e, settings, techNote, message) {
    console.log('DataTables error: ', message);
    });

    loadTickets();

    /** funcion para recargar el contenimo en tiempo corto cuando es hoarario laboral */
    function getReloadInterval() {
        var now = new Date();
        var hours = now.getHours();
        // Return 1 minute during working hours (8 to 17) and 30 minutes otherwise
        if (hours >= 8 && hours < 17) {
            return 60000; // 1 minute in milliseconds
        } else {
            return 1800000; // 30 minutes in milliseconds
        }
    }

    function setReloadInterval() {
        var interval = getReloadInterval();
        setInterval(function() {
            if (table) {
                table.ajax.reload(null, false); // false to not reset the pagination position
            }
        }, interval);
    }
    setReloadInterval();
    
    // Configura el intervalo de actualización
    // setInterval(function() {
    //     if (table) {
    //         table.ajax.reload(null, false); // false para no resetear la posición de la paginación
    //     }
    // }, 60000); // 5000 ms = 5 segundos

});
/** Script para notificar los cambio en los tickets con sonido */


        let lastUpdateTime = null;

        

        function checkForUpdates() {
            $.ajax({
                url: "{{ route('tickets.check-updates') }}",
                method: "GET",
                success: function (data) {
                    if (lastUpdateTime !== null && lastUpdateTime !== data.last_updated_at) {
                        sonido();
                    }
                    lastUpdateTime = data.last_updated_at;
                },
                error: function () {
                    console.error("Error al verificar actualizaciones");
                }
            });
        }

        setInterval(checkForUpdates, 5000);
        

        function sonido() {
        let audio = new Audio('/storage/images/user/notification-sound.mp3');

        $('#enable-sound-notifications').on('click', function() {
            audio.play().then(() => {
                audio.pause(); // Pausar inmediatamente después de reproducir para permitir reproducción futura
                audio.currentTime = 0;
                $('#enable-sound-notifications').hide();
                startCheckingForUpdates();
            }).catch(function(error) {
                console.error('Error al iniciar el audio:', error);
            });
        });
    }

</script>

@endsection