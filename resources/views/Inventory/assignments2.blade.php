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

<section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-3">    
    <div class="container mt-0">
        <div class="form-group">
            <label for="user-search">Buscar Usuario:</label>
            <input type="text" id="user-search" class="form-control" placeholder="Escribe el nombre del usuario">
            <div id="user-results" class="list-group mt-2"></div>
        </div>
        <div id="device-section" style="display:none;">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">Nina Mcintire</h3>
                <p class="text-muted text-center">Software Engineer</p>
            </div>
            <div class="form-group">
                <label for="category-select">Categoría de Dispositivos:</label>
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
            <ul id="device-list" class="list-group mt-3"></ul>
            <button id="assign-devices" class="btn btn-success mt-3">Asignar Dispositivos</button>
        </div>
    </div>
</div>
<!--  -->
<!-- <div class="col-md-9">    
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Equipos seleccionados</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <td>Functional-requirements.docx</td>
                <td>49.8005 kb</td>
                    <td class="text-right py-0 align-middle">
                        <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-info"><i class="fas fa-eye"></i></a>
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>        
                </tbody>
            </table>
        </div>
    </div>
</div> -->

</div>
</div>
</section>
@endsection
@section('js')
<script>
   $(document).ready(function() {
    let selectedUserId = null;
    let selectedDevices = [];

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
        $('#device-section').show();   

        // Resetear los selectores y la lista de dispositivos
        $('#tipoequipo-select').empty().append('<option value="">Seleccione una categoría</option>');
        $('#device-select').empty().append('<option value="">Seleccione un dispositivo</option>');
        $('#device-list').empty();
        selectedDevices = [];

        // Cargar categorías tipoequipo
        $.ajax({
                url: "{{ route('device-assignment.tipoequipo') }}",
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
    }); 

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
                        deviceSelect.append(`<option value="${device.id}">${device.name}</option>`);
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
        if (deviceId && !selectedDevices.includes(deviceId)) {
            selectedDevices.push(deviceId);
            $('#device-list').append(`<li class="list-group-item" data-id="${deviceId}">${deviceName} <button class="btn btn-sm btn-danger float-right remove-device">Quitar</button></li>`);
        }
    });
    // Quitar dispositivo de la lista
    $(document).on('click', '.remove-device', function() {
        const deviceId = $(this).closest('li').data('id');
        selectedDevices = selectedDevices.filter(id => id !== deviceId);
        $(this).closest('li').remove();
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