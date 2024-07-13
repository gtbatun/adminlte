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
                    <table id="ticketsnew-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                        <thead class="table-dark">
                            <tr class="text-center">
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
<!-- <button id="enable-sound-notifications" class="btn btn-warning">Habilitar notificaciones de sonido</button> -->


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

<div class="modal" id="modal-gestion-ticket">
    <div class="modal-dialog modal-fullscreen-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="form-group" >
                <h5 class="modal-title">Gestionar ticket </h5>
                <strong class="text-danger"><span id="ticket-name-title"></span></strong>
                </div>                
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <span id="ticket-description"></span>                
                        <div class="container-fuid">
                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header">
                            <h3 class="card-title">Historial</h3>            
                            <div class="card-tools">
                                <!-- <span class="badge badge-primary" id="data-length"></span> -->
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" title="Contacts">
                                    <i class="fas fa-comments"></i>
                                    <div class="float-right badge rounded-pill bg-primary"  id="data-length" ></div>
                                    <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="data-length"></span></h4> -->
                                </button>
                                <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button> -->
                            </div>            
                        </div>            
                        <div class="card-body">
                                <div class="direct-chat-messages" id="gestiones-container1">
                                    <!-- Messages will be appended here -->
                                </div>
                        </div>      
                        <div class="card-header">
                            <form id="gestionform" method="POST" enctype="multipart/form-data">
                                @csrf                        
                                <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
                            <div id="errorContainer" ></div>
                            <div class="row">                    
                                <!-- inicio seccion de area y categorias -->         
                                        <div class="col-xs-12 col-sm-12 col-md-4 pb-2 d-flex justify-content-end" > 
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="cerrar" >
                                            <label class="form-check-label text-danger" for="status_id"><strong>Cerrar Ticket</strong></label>            
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="2" name="reopen" >
                                            <label class="form-check-label text-success" for="status_id"><strong>Reabrir Ticket</strong></label>            
                                        </div>
                                        </div>
                                </div> 
                                <!-- fin de seccion de botones de cerrar y reabrir ticket -->
                                <div class="input-group">
                                    <textarea name="coment" placeholder="Type Message ..." class="form-control" id="messageInput" rows="1"></textarea>
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="sendMessageButton">Send</button>
                                        <button type="button" class="btn btn-secondary" id="addImageButton"><i class="fas fa-image"></i></button>
                                    </span>
                                </div>
                                <input type="file" name="image[]" id="fileInput" accept="image/*" multiple style="display: none;">
                                </form>
                            <div id="imagePreviewContainer" class="mt-3"></div>
                        </div>
                    </div>        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
        $(document).ready(function() {
        var table = $('#ticketsnew-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('tickets.data2') }}",
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
                { data: 'title' ,searchable: true},
                { data: 'category',searchable: true },
                { data: 'type',searchable: true },
                { data: 'sucursal',searchable: true },
                { data: 'status',searchable: true },
                { data: 'actions', orderable: false}
            ],
            createdRow: function(row, data, dataIndex) {
                $('td', row).eq(3).css('background-color', data.typeColor);
                $('td', row).eq(5).css('background-color', data.typeColorback);
            },
            language: {
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_ ticket por pagina",
                info: "Mostrando _START_ de _END_ de _TOTAL_ ",
                infoFiltered: "(filtrado de un total de _MAX_)",
                emptyTable: "Sin Datos a Mostrar",
                zeroRecords: "No se encontraron coincidencias",
                infoEmpty: "Mostrando 0 de 0 de 0 coincidencias",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente",
                    first: "Primero",
                    last: "Ultimo"
                },
            },
            responsive: true,
            paging: true,
            ordering: true,
            searching: true
        });

        setInterval(function() {
            table.ajax.reload(null, false);
        }, 60000);

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
        /** */
        $(document).on('click', '.modal-gestion-btn', function() {
            var ticketId = $(this).data('ticket-id');
            var ticketTitle = $(this).data('ticket-title');            
            var ticketDescription = $(this).data('ticket-description');
            $('#modal-gestion-ticket').find('#ticket-id').val(ticketId);            
            $('#modal-gestion-ticket').find('#ticket-name-title').text(ticketTitle);
            $('#modal-gestion-ticket').find('#ticket-description').text(ticketDescription);
            $('#modal-gestion-ticket').modal('show');
        });
        
        /** */

        $('#departamento').change(function() {
            var departmentId = $(this).val();
            loadAreas(departmentId);
            $('#categoria').empty().append('<option value="">Seleccionar Categoría</option>');
        });

        $('#area').change(function() {
            var areaId = $(this).val();
            loadCategories(areaId);
        });

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

        /** ----------------------------- */

        // Botón para agregar imágenes
        $('#addImageButton').on('click', function() {
            $('#fileInput').click();
        });
        
        // Manejar el evento de selección de archivos
        $('#fileInput').on('change', function() {
            handleFiles(this.files);
        });

        // Manejar el evento de pegado en el input
        $('#messageInput').on('paste', function(event) {
            handlePaste(event);
        });

         // Enviar el mensaje
        $('#sendMessageButton').on('click', function() {
            // sendMessage();
            if (!$(this).prop('disabled')) { // Verificar si el botón no está desactivado
                sendMessage();
                $(this).prop('disabled', true); // Desactivar el botón después de enviar el formulario
                // Reactivar el botón después de 3 segundos (3000 milisegundos)
                setTimeout(function() {
                    $('#sendMessageButton').prop('disabled', false);
                }, 3000);
            }
        });

        function handleFiles(files) {
            var container = $('#imagePreviewContainer');
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        container.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function handlePaste(event) {
            var items = (event.clipboardData || event.originalEvent.clipboardData).items;
            var container = $('#imagePreviewContainer');
            for (var index in items) {
                var item = items[index];
                if (item.kind === 'file') {
                    var blob = item.getAsFile();
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        container.append(img);
                    };
                    reader.readAsDataURL(blob);
                }
            }
        }

        function updateImagePreviews(files) {
            const container = $('#imagePreviewContainer');
            container.empty(); // Clear previous previews

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        container.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

    /** */
    });
</script>
@endsection
