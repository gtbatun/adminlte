@extends('adminlte::page')
@section('content')

<script src="{{asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/toastr.min.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
<script src="{{asset('assets/js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">

<!-- <link href="https://cdn.datatables.net/v/bs4/dt-2.1.6/r-3.0.3/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/bs4/dt-2.1.6/r-3.0.3/datatables.min.js"></script> 

<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.1.6/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.1.6/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.js"></script> -->



<a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>  

<div class="row">
    <div class="col-12 mt-0 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>         
        @else
            <h3>@lang('Tickets')</h3>
        @endisset
              
    </div>
    
<button id="enable-sound-notifications" class="btn btn-warning">Habilitar notificaciones de sonido</button>

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
                    <table id="tickets-table" class="table table-striped nowrap table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>TIEMPO</th>
                                <th>ID</th>
                                <th>TICKET</th>
                                <th>CATEGORIA</th>
                                <th>ASIGNADO</th>
                                <th>SUCURSAL</th>
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


<!---------------------------------- Modal de reasignar ticket  ---------------------->
<div class="modal" id="modal-reasig-ticket">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reasignar ticket <strong class="text-danger"><span id="ticket-name-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <!-- -->
                <form id="reasignticket" action="{{route('ticket.reasig')}}"  method="post">
                    @csrf
                    <input type="hidden" name="ticket_id" id="ticket-id">
                    <input type="hidden" name="departmentOld_id" id="ticket-department">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="col-xs-12 col-sm-4 col-md-12 mt-2">
                    <label for="departamento">Departamento</label>
                    <select name="department_id" id="departamento" class="form-control" required>
                        <option value="">Seleccionar Departamento</option>
                    </select>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-12 mt-2">
                        <label for="area">Areas</label>
                        <select name="area_id" id="area" class="form-control" required>
                            <option value="">Seleccionar Área</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-12 mt-2">
                        <label for="category">Categoria</label>
                        <select name="category_id" id="categoria" class="form-control" required>
                            <option value="">Seleccionar Categoría</option>
                        </select>
                    </div>
                    <button type="submit" id="submit-reasign-ticket" class="btn btn-primary mt-3">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('Gestion.modalgestion')
@endsection

@section('js')
<script>   
    var table;  

    $(document).ready(function() {    
        // document.addEventListener("DOMContentLoaded", function() {   
        let audio = new Audio('/storage/images/user/notification-sound.mp3');

        function loadTickets() {
            // table = $('#tickets-table').DataTable({
            table = new DataTable('#tickets-table',{
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
                    { data: 'gestionTime', visible: false },
                    { data: 'id'},
                    { data: 'title' },
                    { data: 'category' },
                    { data: 'type' },
                    { data: 'sucursal' },
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
        // Función para cargar departamentos
        function loadDepartments() {
        $.get("{{ route('departments.data') }}", function(data) {
            var departments = data;
            $('#departamento').empty();
            $('#departamento').append('<option value="">Seleccionar Departamento</option>');
            $.each(departments, function(index, department) {
                $('#departamento').append('<option value="' + department.id + '">' + department.name + '</option>');
            });
        });
        }
        /** */
        function loadAreas(departmentId) {
        $.get("/get-area/" + departmentId , function(data) {
            var areas = data;
            $('#area').empty();
            $('#area').append('<option value="">Seleccionar Área</option>');
            $.each(areas, function(index, area) {
                $('#area').append('<option value="' + area.id + '">' + area.name + '</option>');
            });
        });
        }

        function loadCategories(areaId) {
            $.get("/get-category/" + areaId , function(data) {
                var categories = data;
                $('#categoria').empty();
                $('#categoria').append('<option value="">Seleccionar Categoría</option>');
                $.each(categories, function(index, category) {
                    $('#categoria').append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            });
        }
         
        // Cargar los tickets al cargar la página
        loadTickets();
        /** */
        // Función para establecer el intervalo de recarga de la tabla
        function setReloadInterval() {
            var interval = getReloadInterval();
            setInterval(function() {
                if (table) {
                    // table.ajax.reload(null, false);
                    // checkForUpdates();
                    checkNewNotifications();
                }
            }, interval);
        }
        // Función para obtener el intervalo de recarga basado en la hora del día
        function getReloadInterval() {
            var now = new Date();
            var hours = now.getHours();
            return (hours >= 8 && hours < 18) ? 3000 : 1800000;
            // return (hours >= 8 && hours < 17) ? 60000 : 1800000;
        } 
        /** Script para notificar los cambio en los tickets con sonido */
        let lastUpdateTime = null;
        // Función para verificar actualizaciones y reproducir sonido
        function checkForUpdates() {
            $.ajax({
                url: "{{ route('tickets.check-updates') }}",
                method: "GET",
                success: function(data) {
                    // console.log(data);
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
        /** --------------------------------------------------------------  */
        // let lastUpdateTime = null;
        // Función para verificar actualizaciones y reproducir sonido
        function checkNewNotifications() {
            $.ajax({
                url: "{{ route('tickets.check-new-notifications') }}",
                method: "GET",
                success: function(data) {
                    console.log(data);
                    if (data.unread_notifications_count > 0) {
                        sonido();
                        // alert('Función de captura de pantalla no implementada');
                    }
                    lastUpdateTime = data.last_updated_at;
                },
                error: function() {
                    console.error("Error al verificar actualizaciones");
                }
            });
        } 

        /** ------------------------------------------------------------------- */
         // Función para reproducir sonido de notificación
        function sonido() {
            audio.play().catch(function(error) {
                console.error('Error al reproducir el sonido de notificación:', error);
            });
        }
         // Establecer el intervalo de recarga     
        setReloadInterval();  
        
        // Manejar la habilitación de notificaciones de sonido
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
        //         // setInterval(checkForUpdates, 5000);
            }).catch(function(error) {
                console.error('Error al iniciar el audio:', error);
            });
        });      

        // $('#tickets-table').on('error.dt', function(e, settings, techNote, message) {
        //     console.log('DataTables error: ', message);
        // });        

        

        // Manejar el clic en el botón de reasignar
        $(document).on('click', '.modal-reasig-btn', function() {
            var ticketDepartment = $(this).data('ticket-department');
            var ticketTitle = $(this).data('ticket-title');
            var ticketId = $(this).data('ticket-id');

            $('#modal-reasig-ticket').find('#ticket-id').val(ticketId);
            $('#modal-reasig-ticket').find('#ticket-name-title').text(ticketTitle);
            $('#modal-reasig-ticket').find('#ticket-department').val(ticketDepartment);
            
            loadDepartments();

            $('#modal-reasig-ticket').modal('show');
        });
            
        // Manejar el cambio de departamento
        $('#departamento').change(function() {
            var departmentId = $(this).val();
            loadAreas(departmentId);
            $('#categoria').empty().append('<option value="">Seleccionar Categoría</option>');
        });

        // Manejar el cambio de área
        $('#area').change(function() {
            var areaId = $(this).val();
            loadCategories(areaId);
        });

        

    });
</script>
@endsection
