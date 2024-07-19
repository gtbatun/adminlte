@extends('adminlte::page')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<section class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Asignar</h1>
        </div>
    </div>
</div>
</section>

<!-- <section class="content"> -->
<div class="container-fluid">
<div class="row">
    <!-- Sección izquierda: Buscar y seleccionar dispositivos -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="user-search">Buscar Usuario:</label>
            <input type="text" id="user-search" class="form-control" placeholder="Escribe el nombre del usuario">
            <div id="user-results" class="list-group mt-2"></div>
        </div>
        
        <div id="user-details" style="display:none;" class="card-body box-profile">
                <!-- <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                </div> -->
                
                <!-- <h5 class="card-title text-center" id="employee-name"></h5>
                <p class="text-muted text-center" id="employee-department"></p>
                <p class="text-muted text-center"><span id="employee-branch"></span></p> -->
        </div>
        <!--  -->
        <div class="card card-succes" >
            <div class="card-header">
                <h5 class="card-title">Dispositivos Asignados</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>                            
                            <th>Categoria</th>
                            <th>Nombre</th>
                            <!-- <th>Acciones</th> -->
                        </tr>
                    </thead>
                    <tbody id="user-devices">
                    </tbody>
                </table>
            </div>
        </div>
        <!--  -->
        <div id="device-section" style="display:none;">
            <div class="form-group">
                <label for="tipoequipo-select">Categoría de Dispositivos:</label>
                <select id="tipoequipo-select" class="form-control">
                    <option value="">Seleccione una categoría</option>
                </select>
            </div>
            <div class="form-group">
                <label for="device-select">Dispositivos Disponibles:</label>
                <select id="device-select" class="form-control">
                    <option value="">Seleccione un dispositivo</option>
                </select>
            </div>
            <button id="add-device" class="btn btn-primary">Agregar Dispositivo</button>
        </div>
    </div>
     <!-- Sección derecha: Tabla de dispositivos seleccionados -->
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Dispositivos seleccionados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>                            
                            <th>Categoria</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="device-select">
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted">                
                <button id="assign-devices" class="btn btn-primary mt-1">Asignar</button>
            </div>
        </div>
    </div>


</div>
</div>
<!-- </section> -->
@endsection
@section('js')
<script>
   $(document).ready(function() {
    let selectedUserId = null;
    let selectedDevices = [];

     // Token CSRF
     const csrfToken = $('meta[name="csrf-token"]').attr('content');

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

        $.ajax({ //`/device-assignment/user-details/${selectedUserId}` 
            url: `/device-assignment/user-details/${selectedUserId}`,
            method: 'GET',
            success: function(data) {
                $('#employee-name').text(data.name);
                $('#employee-department').text(data.department ? data.department.name : 'N/A');
                $('#employee-branch').text(data.sucursal ? data.sucursal.name : 'N/A');
                $('#user-details').show();
                $('#device-section').show();
                $('#device-asignados').show();

                // Resetear los selectores y la lista de dispositivos
                $('#category-select').empty().append('<option value="">Seleccione una categoría</option>');
                $('#device-select').empty().append('<option value="">Seleccione un dispositivo</option>');
                $('#device-list').empty();
                selectedDevices = [];

                /** -------------------------------------------    */
                 // Solicitar los dispositivos del usuario
                 $.ajax({
                    url: `/user/${selectedUserId}/devices`,
                    method: 'GET',
                    success: function(data) {
                        var devicesList = $('#user-devices');
                        devicesList.empty(); // Limpia la lista de dispositivos
                        // console.log(data);

                        if (data.length > 0) {                            
                            
                            $.each(data, function(index, device) {
                                // devicesList.append('<li>' + device.tipodevice.name +':  '+ device.name +'</li>');
                                // devicesList.append(`<tr><td>${device.id}</td><td>${device.name}</td><td><button class="btn btn-sm btn-danger remove-device">Quitar</button></td></tr>`);
                                devicesList.append(`<tr><td>${device.tipo_equipo_id}</td><td>${device.name}</td></tr>`);
       
                            });
                        } else {
                            devicesList.append('No tiene dispositivos asignados.');
                        }
                    }
                });
                /**-------------------------------------------       */


                // Cargar categorías tipoequipo
                $.ajax({
                    url: "{{ route('device-assignment.tipoequipo')}}",
                    method: 'GET',
                    success: function(data) {
                        let tipoequipoSelect = $('#tipoequipo-select');
                        // tipoequipoSelect.empty();
                        // tipoequipoSelect.append('<option value="">Seleccione una categoría</option>');
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
                        // deviceSelect.append(`<option value="${device.id}" data-tipodevice="${device.tipodevice.name}">${device.name}</option>`);
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
        if (deviceId && !selectedDevices.includes(deviceId)) {
            selectedDevices.push(deviceId);
            $('#device-list').append(`<tr data-id="${deviceId}"><td>${deviceTipodevice}</td><td>${deviceName}</td><td><button class="btn btn-sm btn-danger remove-device">Quitar</button></td></tr>`);
       }
    });


    // Quitar dispositivo de la lista
    $(document).on('click', '.remove-device', function() {
        const deviceId = $(this).closest('tr').data('id');
        selectedDevices = selectedDevices.filter(id => id !== deviceId);
        $(this).closest('tr').remove();
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
                    device_ids: selectedDevices
                },
                success: function(response) {
                    alert('Dispositivos asignados correctamente.');
                    selectedDevices = [];
                    $('#device-list').empty();
                    $('#device-section').hide();
                    $('#user-search').val('');
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al asignar los dispositivos. Por favor, inténtelo de nuevo.');
                }
            });
        } else {
            alert('Por favor, seleccione un usuario y al menos un dispositivo.');
        }
    });

}); 
</script>
@endsection