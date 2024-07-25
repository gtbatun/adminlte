@extends('adminlte::page')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<section class="content-header">
<!-- <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Asignar</h1>
        </div>
    </div>
</div> -->
</section>

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
                            <input type="text" class="form-control" id="coment" placeholder="Comentarios">
                        </div>
                    </div>
                    <div class="col-md-2 float-right"> 
                        <div class="form-group">                       
                            <button id="add-device" class="btn btn-primary ">Agregar</button>
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
                            <tr>                            
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
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr> 
                                <!-- <th>Inventory_Id</th> -->
                                <!-- <th>Id-device</th> -->
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
        </div>
    </div>
</div>


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
                <input type="hidden" id="delete-inventory-id">
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

@endsection
@section('js')

<script>
    $(document).ready(function() {
    let selectedUserId = null;
    let selectedDevices = [];
    let deviceIdToDelete = null;
    // Token CSRF
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Delegar evento para mostrar la sección de dispositivos cuando se hace clic en el botón flotante
    $(document).on('click', '#show-device-section', function() {
        $('#device-section').toggle();
    })

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

        resetSelections();

        $.ajax({  
            url: `/device-assignment/user-details/${selectedUserId}`,
            method: 'GET',
            success: function(data) {
                $('#employee-name').text(data.name);
                $('#employee-department').text(data.department ? data.department.name : 'N/A');
                $('#employee-branch').text(data.sucursal ? data.sucursal.name : 'N/A');
                $('#user-image').attr('src','../storage/images/user/'+data.image); // Asegúrate de que `data.image` contiene la URL de la imagen
                $('#user-details').show();
                $('#device-asignados').show();

                // Cargar dispositivos asignados al usuario
                loadUserDevices(selectedUserId);

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
        });
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
                        deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipodevice.name}">${device.name}</option>`);
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

        if (deviceId && !selectedDevices.some(device => device.deviceId === deviceId)) {
            selectedDevices.push({ deviceId, coment }); // Guardar ID del dispositivo y comentario

            $('#device-seleccionado').show();
            $('#device-list').append(`<tr data-id="${deviceId}">
                    <td>${deviceTipodevice}</td>
                    <td>${deviceName}</td>
                    <td>${coment}</td>
                    <td><button class="btn btn-sm btn-danger remove-device">X</button></td>
                </tr>`);
            
            // -----------------------     Reseteo del selector de dispositivos -----------------------
            const tipoequipoId = $('#tipoequipo-select').val();
            if (tipoequipoId) {
                $.ajax({
                    url: `/device-assignment/devices/${tipoequipoId}`,
                    method: 'GET',
                    success: function(data) {
                        let deviceSelect = $('#device-select');
                        deviceSelect.empty();
                        deviceSelect.append('<option value="">Seleccione un dispositivo</option>');
                        data.forEach(device => {
                            deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipo_equipo_id}">${device.name}</option>`);
                        });
                        $('#coment').val('');
                    }
                });
            }            
        }
    });

    // Quitar dispositivo de la lista
    $(document).on('click', '.remove-device', function() {
        const deviceId = $(this).closest('tr').data('id');
        selectedDevices = selectedDevices.filter(id => id !== deviceId);
        $(this).closest('tr').remove();
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

    // Función para recargar los dispositivos asignados al usuario
    function loadUserDevices(userId) {
        $.ajax({
            url: `/user/${userId}/devices`,
            method: 'GET',
            success: function(data) {
                var devicesList = $('#user-devices');
                devicesList.empty(); // Limpia la lista de dispositivos

                if (data.length > 0) {
                    $.each(data, function(index, device) {
                        devicesList.append(`<tr>
                        
                        <td>${device.tipodevice.name}</td>
                        <td>${device.name}</td>
                        <td>
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
        $('#user-search').val('');
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
});
</script>
@endsection

