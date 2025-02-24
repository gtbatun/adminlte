@extends('adminlte::page')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="{{asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/plugins/toastr.min.js')}}"></script> -->

<!-- <script src="{{asset('assets/js/datatables.min.js')}}"></script> -->
<style>
    .floating-button {            
            position: fixed;
            border-radius: 50%;
            background-color: green;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            font-size: 26px;
            bottom: 15px;
            right: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        
    .floating-button3 {
        position: absolute;
        bottom: -19px; /* Ajuste para mover más cerca del borde inferior */
        right: -20px; /* Ajuste para mover más cerca del borde derecho */
        z-index: 1000;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 24px;
        line-height: 5px;
        text-align: center;
        inline-size: false;
        background-color: green;
        color: white;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }
    .floating-button:hover {
        background-color: #218838;
    }
    .table-container {
        position: relative;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    #device-section {
        display: none;
        transition: all 0.3s ease;
    }

</style>


<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="form-group">
                <label for="user-search">Buscar Usuario:</label>
                <input type="text" id="user-search" autofocus autocomplete="off" class="form-control" placeholder="Escribe el nombre del usuario">
                <div id="user-results" class="list-group mt-2"></div>
            </div>
            <div class="container-fluid">
                <div id="user-details" class="text-center" style="display:none;">                
                    <div class="card-body box-profile">
                    <div class="text-center">
                        <img id="user-image" src="" alt="User Image" class="img-fluid rounded-circle mb-2" style="width: 100px; height: 100px;">                                   
                    </div>
                        <h5 class="profile-username text-center" id="employee-name"></h5>
                        <span id="employee-department"></span>
                        <span id="employee-branch"></span>
                        <!-- <span id="employee-id"></span> Se muestra id del usuario o empleado seleccionado -->
                    </div>
                </div>
            </div>
                      
            <div id="device-section" style="display:none;">
                <div class="d-flex align-items-end">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipoequipo-select">Categoría de Dispositivos:</label>
                            <select id="tipoequipo-select" class="form-control">
                                <option value="">Seleccione una categoría</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="device-select">Dispositivos Disponibles:</label>
                            <select id="device-select" class="form-control">
                                <option value="">Seleccione un dispositivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="coment">Comentario</label>
                            <input type="text" class="form-control" id="coment" placeholder="Comentarios" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3"> 
                        <div class="form-group">
                            <div class="btn-group">                       
                                <button id="add-device" class="btn btn-primary " title="Agegar al carrito">Agregar <i class="fas fa-shopping-cart"></i></button>
                            </div>
                            <div class="btn-group"><!-- Botón para abrir el modal -->                            
                                <button type="button" class="btn btn-success"  data-user-id="" data-toggle="modal" data-target="#createModal" title="Agregar nuevo equipo y asignar al usuario seleccionado">Nuevo  <i class="fas fa-desktop"></i></button> 
                            </div>
                            <!-- Incluir el modal -->
                            @include('Device.createmodal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="row">        
        <!--------------------------- Sección derecha: Tabla de dispositivos seleccionados ---------------------->
        <div class="container-fluid">
            <div id="device-seleccionado" style="display: none;" class="card card-info card-outline">
                <div class="card-header">
                    <h5 class="card-title">Dispositivos seleccionados</h5>
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                        <!-- <button class="btn btn-primary" id="show-device-section">Agregar Dispositivo</button> -->
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <!-- <tr> <th>Id-device</th> -->
                                <th>Categoria</th>
                                <th>Nombre</th>
                                <th>Comentario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="device-list">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted">                
                    <button id="assign-devices" class="btn btn-primary mt-1">Asignar</button>
                    <!-- Botón flotante -->
                </div>
            </div>
        </div>
        <!----------------------- Sección izquierda: Buscar y seleccionar dispositivos --------------------->
        <div class="container-fluid" >
            <div id="device-asignados" style="display: none;" class="card card-success card-outline" >
                <div class="card-header">
                    <h5 class="card-title">Dispositivos Asignados</h5>
                    
                    <div class="card-tools">
                        <!-- <button class="btn btn-sm btn-success add_mantto" data-user_id="${device.user_id}" data-device_id="${device.id}" data-inventory_id="${device.inventory_id}">                                
                            <i class="fas fa-tools"></i>
                        </button> -->
                        <button class="btn btn-sm btn-danger " id="remove-all" data-toggle="modal" data-target="#modal-remove-all">
                            <i class="far fa-trash-alt"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" ><i class="fa fa-check-square"></i></button> -->
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr> 
                                <th>Id-device</th>
                                <th>Inventory_Id</th>
                                 <th></th>
                                <th>Categoria</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="user-devices">
                        </tbody>
                        <!-- <button class="floating-button" id="show-device-section"><span>+</span></button> -->
                    </table>
                </div>
                <button class="floating-button" id="show-device-section"><i class="fas fa-plus"></i></button>
            </div>
            
            <!-- <button  id="show-device-section"><i class="fas fa-user"></i></button>  -->
        </div>
    </div>
</div>
<!-- se incluye el modal de mantenimiento -->
@include('Inventory.manttomodal')

<!-- Modal para confirmación de eliminación -->
<div class="modal fade" id="deleteDeviceModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDeviceModalLabel">Eliminar Dispositivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            <div class="form-group">
                <!-- <label for="">inventory_id</label> -->
                <input type="text" id="delete-inventory-id">
            </div>
            <div class="form-group">
                <!-- <label for="">device_id</label> -->
                <input type="hidden" id="delete-device-id">
            </div>
            <div class="form-group">
                <!-- <label for="">usuario_id</label> -->
                <input type="hidden" id="staff_id" value="{{auth()->user()->id}}">
            </div>
            <div class="form-group">
                <label for="delete-status">Seleccionar Estado:</label>
                <select id="delete-status" class="form-control" >
                  <!-- Las opciones se llenarán dinámicamente -->   	
                </select>
            </div>
            <div class="form-group">
                <label for="delete-comment">Comentario:</label>
                <textarea id="delete-comment" class="form-control" rows="3"></textarea>
            </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para reasignar todos los equipos -->
<div class="modal fade" id="modal-remove-all" tabindex="-1" role="dialog" aria-labelledby="reassignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Reasignar Dispositivos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            
            <div class="form-group">
                <p>Selecciona el usuario al que deseas asignar los dispositivos seleccionados:</p>                
                <!-- Selección de nuevo usuario -->
                <select id="newUserId" class="form-control">
                    <option value="">Seleccione un usuario</option>
                </select>
            </div>
                
            <div class="form-group">
                <label for="delete-comment">Comentario:</label>
                <textarea id="delete_comment" class="form-control" rows="3"></textarea>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmReassignBtn" class="btn btn-primary">Confirmar Reasignación</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')

<script>
    $(document).ready(function() {
    let selectedUserId = null;
    let selectedDevices = [];
    let deviceIdToDelete = null;

    // Verificar si hay un parámetro user_id en la URL
    const urlParams = new URLSearchParams(window.location.search);
        const userIdFromUrl = urlParams.get('user_id');
        if (userIdFromUrl) {
            selectedUserId = userIdFromUrl;
            cargarDatosUsuario(selectedUserId);
        }

    // Token CSRF
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Delegar evento para mostrar la sección de dispositivos cuando se hace clic en el botón flotante
    $(document).on('click', '#show-device-section', function() {
        $('#device-section').toggle();
    })


    /*** ---------------------- script para modal de reasignacion de equipso en modal remove-all ----------------------- --------------------------------------------------------------*/
    // Manejar la apertura del modal
    $('#remove-all').on('click', function() {
        selectedDevices = [];  // Asegúrate de limpiar el arreglo antes de usarlo

        // Recolectar los checkboxes seleccionados
        $('.device-checkbox:checked').each(function() {
            selectedDevices.push($(this).val());
        });

        if (selectedDevices.length > 0) {
            // Guardar los dispositivos seleccionados en una variable global o en un input oculto si es necesario
            window.selectedDevices = selectedDevices;  // Puedes ajustar esto según tus necesidades
        } else {
            alert('Por favor, selecciona al menos un dispositivo.');
            return false;  // Evitar que el modal se abra si no hay dispositivos seleccionados
        }
        // seccion para solicitar la lista de usuarios
        // Rellenar el select de estados
        $.ajax({
            url: '/users', // usuarios activos
            method: 'GET',
            success: function(data) {
                let userSelect = $('#newUserId');
                // userSelect.empty(); // Limpia el select                
                // userSelect.append(`<option value="">Seleccione un dispositivo</option>`);
                data.forEach(user => {
                    userSelect.append(`<option value="${user.id}">${user.name}</option>`);
                });
            }
        });
    });

    // Manejar la confirmación dentro del modal
    $('#confirmReassignBtn').on('click', function() {
        let newUserId = $('#newUserId').val();
        let delete_comment = $('#delete_comment').val();

        if (!newUserId) {
            alert('Por favor, selecciona un usuario.');
            return;
        }
        console.log(selectedDevices);
        console.log(delete_comment);

        // Hacer la solicitud AJAX para reasignar los dispositivos seleccionados
        $.ajax({
           url: `/device/unassign-devices`,  // Cambia a tu ruta en Laravel            
            method: 'POST',
            data: {
                deviceIds: window.selectedDevices,  // Usar los dispositivos seleccionados
                newUserId: newUserId,
                delete_comment: delete_comment,
                _token: '{{ csrf_token() }}' // Token CSRF para seguridad
                
            },
            success: function(response) {
                if (response && response.success) {
                    alert('Dispositivos reasignados exitosamente.');
                    $('#modal-remove-all').modal('hide');
                    $('#delete_comment').val('');
                    $('#newUserId').val('');
                    loadUserDevices(selectedUserId);
                } else {
                    alert('Error en la respuesta del servidor.');
                }
            },
            error: function(response) {
                alert('Error al reasignar los dispositivos.');
            }
        });
    });

    
    $('#modal-remove-all').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();  // Elimina el fondo oscuro cuando el modal se cierre
        selectedDevices = [];
    });


    /** -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    // Buscar usuarios
    $('#user-search').on('input', function() {
        const query = $(this).val();
        if (query.length >= 2) {
            $.ajax({
                url: "{{ route('user.searchUsers') }}",
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    let userResults = $('#user-results');
                    userResults.empty();
                    data.forEach(user => {
                        userResults.append(`<a href="#" class="list-group-item list-group-item-action user-item" data-id="${user.id}">${user.name}</a>`);
                    });
                }
            });
        }
    });

    // Seleccionar usuario
    $(document).on('click', '.user-item', function() {
        selectedUserId = $(this).data('id');
        $('#user-search').val($(this).text());
        $('#user-results').empty();
        // console.log('desdeleselec: '+selectedUserId);
        resetSelections();
        cargarDatosUsuario(selectedUserId);
    });

    // Cargar dispositivos según el tipoequipo seleccionada
    $('#tipoequipo-select').on('change', function() {
        const tipoequipoId = $(this).val();
        if (tipoequipoId) {
            $.ajax({
                url: `/device-assignment/devices/${tipoequipoId}`,
                method: 'GET',
                success: function(data) {
                    let deviceSelect = $('#device-select');
                    deviceSelect.empty();
                    deviceSelect.append('<option value="">Seleccione un dispositivo</option>');
                    data.forEach(device => {
                        // deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipodevice.name}">${device.name}</option>`);
                        // Verificar si el dispositivo ya está en la lista de seleccionados
                        if (device && !selectedDevices.some(selectedDevice => selectedDevice.deviceId == device.id)) {  // ===
                            // Si no está seleccionado, añadirlo como opción habilitada
                            deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipodevice.name}">${device.name}</option>`);
                        } else {
                            // Si está seleccionado, añadirlo como opción deshabilitada
                            deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipodevice.name}" disabled>${device.name}</option>`);
                        }
                    });
                }
            });
        } else {
            $('#device-select').empty().append('<option value="">Seleccione un dispositivo</option>');
        }
    });

    // Agregar dispositivo a la lista
    $('#add-device').on('click', function() {
        const deviceId = $('#device-select').val();
        const deviceName = $('#device-select option:selected').text();
        const deviceTipodevice = $('#device-select option:selected').data('tipodevice');
        const coment = $('#coment').val();


        if (deviceId && !selectedDevices.some(device => device.deviceId == deviceId)) {
            selectedDevices.push({ deviceId, coment }); // Guardar ID del dispositivo y comentario

            $('#device-seleccionado').show();
            $('#device-list').append(`<tr data-id="${deviceId}">
                    <td>${deviceTipodevice}</td>
                    <td>${deviceName}</td>
                    <td>${coment}</td>
                    <td><button class="btn btn-sm btn-danger remove-device">X</button></td>
                </tr>`);

            // Limpiar el campo de comentario
            $('#coment').val('');
            $('#tipoequipo-select').empty().append('<option value="">Seleccione una categoría</option>');
            $('#device-select').empty().append('<option value="">Seleccione un dispositivo</option>');
            searchCategories();
        }
        // console.log(selectedDevices);
    });
            


    // Quitar dispositivo de la lista
    $(document).on('click', '.remove-device', function() {
        const deviceId = $(this).closest('tr').data('id');
        // console.log("Intentando eliminar dispositivo con ID:", deviceId); 
        // console.log("Antes de eliminar:", selectedDevices);
        // Filtrar la lista para eliminar el dispositivo
        selectedDevices = selectedDevices.filter(item => item.deviceId != deviceId); // deberian ser === pero solo funciono corectamente con !=
        $(this).closest('tr').remove();

        // console.log("Después de eliminar:", selectedDevices);        
        // console.log(selectedDevices);
    });


    // Quitar dispositivo al usuario(dispositivos asignados)
    // Mostrar modal para confirmación de eliminación
    $(document).on('click', '.remove-deviceAssing', function() {
        deviceIdToDelete = $(this).data('device_id');
        inventoryIdToDelete = $(this).data('inventory_id');
        $('#deleteDeviceModal').modal('show');
        $('#delete-device-id').val(deviceIdToDelete); // Usa .val() para establecer el valor en un input o .text() para un div/span/p
        $('#delete-inventory-id').val(inventoryIdToDelete); 
        $('#delete-comment').val(''); // Resetea el comentario    
       

        // Rellenar el select de estados
        $.ajax({
            url: '/statuses',
            method: 'GET',
            success: function(data) {
                let statusSelect = $('#delete-status');
                statusSelect.empty(); // Limpia el select
                data.forEach(status => {
                    statusSelect.append(`<option value="${status.id}">${status.name}</option>`);
                });
            }
        });
    });
    // Confirmar eliminación
    $('#confirm-delete').on('click', function() {
        
        const deleteComment = $('#delete-comment').val();
        const inventory_id =  $('#delete-inventory-id').val();
        const staff_id =  $('#staff_id').val();
        const selectedStatus = $('#delete-status').val();
        if (deviceIdToDelete && deleteComment && selectedStatus) {
            $.ajax({
                url: `/device-assignment/delete-device/${deviceIdToDelete}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    comment: deleteComment,
                    inventory_id: inventory_id,
                    staff_id: staff_id,
                    status_id: selectedStatus
                },
                success: function(response) {
                    $('#deleteDeviceModal').modal('hide');
                    loadUserDevices(selectedUserId); // Recargar los dispositivos asignados
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al eliminar el dispositivo. Por favor, inténtelo de nuevo.');
                }
            });
        } else {
            console.log('deleteComment: ' + deleteComment);
            console.log('deviceIdToDelete: ' + deviceIdToDelete);
            alert('Por favor, ingrese un comentario.');
        }
    });

    // Asignar dispositivos al usuario
    $('#assign-devices').on('click', function() {
        if (selectedUserId && selectedDevices.length > 0) {
            $.ajax({
                url: "{{ route('device-assignment.assign') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: selectedUserId,
                    devices: selectedDevices
                },
                success: function(response) {
                    alert('Dispositivos asignados correctamente.');
                    resetForm(); // Llama a la función para resetear el formulario y las selecciones
                    loadUserDevices(selectedUserId); // Recargar los dispositivos asignados
                    searchCategories();
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al asignar los dispositivos. Por favor, inténtelo de nuevo.');
                }
            });
        } else {
            alert('Por favor, seleccione un usuario y al menos un dispositivo.');
        }
    });

   

    // Función para resetear las selecciones
    function resetSelections() {
        $('#tipoequipo-select').empty().append('<option value="">Seleccione una categoría</option>');
        $('#device-select').empty().append('<option value="">Seleccione un dispositivo</option>');
        $('#device-list').empty();
        // $('#user-devices').empty();
        selectedDevices = [];        
        $('#device-seleccionado').hide();
        // $('#device-section').hide();
    }

    function resetForm() {
        $('#tipoequipo-select').empty().append('<option value="">Seleccione una categoría</option>');
        selectedDevices = [];
        $('#device-list').empty();
        $('#device-section').hide();
        $('#device-seleccionado').hide();
        // $('#user-search').val('');
    }

    function searchCategories() {
        // Cargar categorías de tipoequipo
        $.ajax({
            url: "{{ route('device-assignment.tipoequipo')}}",
            method: 'GET',
            success: function(data) {
                let tipoequipoSelect = $('#tipoequipo-select');
                data.forEach(tipoequipo => {
                    tipoequipoSelect.append(`<option value="${tipoequipo.id}">${tipoequipo.name}</option>`);
                });
            }
        });
    }

    // Función para cargar datos del usuario
    function cargarDatosUsuario(userId) {
            $.ajax({
                url: `/device-assignment/user-details/${userId}`,
                method: 'GET',
                success: function(data) {
                    $('#employee-name').text(data.name);
                    $('#employee-department').text(data.department ? data.department.name : 'N/A');
                    $('#employee-branch').text(data.sucursal ? data.sucursal.name : 'N/A');
                    $('#employee-id').text(data.id);
                    $('#user-image').attr('src', '../storage/images/user/' + data.image);
                    $('#user-details').show();
                    $('#device-asignados').show();
                    
                    // Actualiza el data-user-id del botón que abre el modal
                    $('.btn-success[data-target="#createModal"]').data('user-id', data.id);
                    // Cargar dispositivos asignados al usuario
                    loadUserDevices(userId);
                    // Asignar el user-id al botón que abre el modal
                    $('button[data-target="#createModal"]').data('user-name', data.name);
                    // Cargar categorías de tipoequipo
                    searchCategories();

                    // Redirigir sin parámetros
                    if (history.pushState) {
                        const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        window.history.pushState({path: cleanUrl}, '', cleanUrl);
                    }
                }
            });
        }

});

 // Función para recargar los dispositivos asignados al usuario
 function loadUserDevices(userId) {
        $.ajax({
            url: `/user/${userId}/devices`,
            method: 'GET',
            success: function(data) {
                // console.log(data);
                var devicesList = $('#user-devices');
                devicesList.empty(); // Limpia la lista de dispositivos

                if (data.length > 0) {
                    $.each(data, function(index, device) {
                        devicesList.append(`<tr>
                        <td>${device.id}</td>
                        <td>${device.inventory_id}</td>
                        <td><input type="checkbox" class="device-checkbox" value="${device.inventory_id}"></td>
                        <td>${device.tipodevice}</td>
                        <td>${device.name}</td>
                        <td>
                            <button class="btn btn-sm btn-success add_mantto" data-user_id="${device.user_id}" data-device_id="${device.id}" data-inventory_id="${device.inventory_id}">                                
                                <i class="fas fa-tools"></i>
                            </button>
                            <button class="btn btn-sm btn-danger remove-deviceAssing" data-device_id="${device.id}" data-inventory_id="${device.inventory_id}">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </td>
                        </tr>`);
                    });
                } else {
                    devicesList.append('<tr><td colspan="3">No tiene dispositivos asignados.</td></tr>');
                }
            }
        });
    }
</script>
@endsection

