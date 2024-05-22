
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


@extends('adminlte::page')
@section('content')
<div class="container-fuid" >
    <div class="col-12 mt-0 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>         
        @else
            <h3 >@lang('Tickets')</h3>
        @endisset
        <a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>        
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

<div class="container-fluid">
    <div class="card fluid">    
    <div class="table-responsive ">
    <table id="tickets-table" class="table table-bordered shadow-sm mt-5 table-striped" >
        <thead  class="table-dark ">
            <tr class="text-center">
                <th>ID</th>
                <th>TICKET</th>
                <th>CATEGORIA</th>
                <th>Sucursal</th>
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
@stop

<script>
    $(document).ready(function() {
        var table = $('#tickets-table').DataTable({
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
                { data: 'id' },
                { data: 'title' },
                { data: 'category' },
                { data: 'sucursal' },
                { data: 'area' },
                { data: 'status' },
                { data: 'actions', orderable: false, searchable: false }
            ],
            responsive: true,
            scrollY: '650px', // Set the height of the scrollable area
                scrollCollapse: true, // Enable scrolling
                paging: true, // Enable pagination
                
        });

        // Configura el intervalo de actualización
        setInterval(function() {
            table.ajax.reload(null, false); // false para no resetear la posición de la paginación
        }, 10000); // 5000 ms = 5 segundos
    });

    $('#tickets-table').on('error.dt', function(e, settings, techNote, message) {
    console.log('DataTables error: ', message);
    });
</script>

