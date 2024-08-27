@extends('adminlte::page')
@section('content')
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<div>    
    @if(Session::get('success'))

    @endif

    <div class="d-grid gap-2 d-md-flex justify-content-md-end m-2"><!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Nuevo equipo</button> 
    </div>
    <!-- Incluir el modal -->
    @include('Device.createmodal')
    <!-- Modal de mantenimientos  -->
    @include('Inventory.manttomodal')
    
      
    @isset($devices)  
    <div class="container-fluid">
            <div class="col-12 mt-1">
                <div class="card fluid">
                    <div class="card-body ">
                        <div class="table-responsive" >
                            <table id="tb-invent" class="table table-bordered shadow-lg mt-2 table-striped  ">
                                <thead  class="table-dark ">
                                    <tr>
                                        <th>ID</th>
                                        <th>TIPO</th>
                                        <th>NOMBRE</th>
                                        <th>DEPARTAMENTO</th>
                                        <th>USUARIO</th>
                                        <th>SUCURSAL</th>
                                        <th>ESTATUS</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
    </div>
       
    @else
    <p>No hay Equipos creados</p>
   @endisset
</div>
<!-- Modal de asignacion de device  -->
<div class="modal" id="modal-asignar-device">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-name" id="modal-titulo">Equipo: <strong class="text-danger"><span id="device-name"></span></strong> Tipo: <span class="text-danger" id="device-tipodevice"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <!-- action="{{ route('inventory.store') }}" -->
                <form  method="post">
                    @csrf
                    <input type="hidden" name="device_id" id="device-id">
                    <div class="form-group">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Seleccione un usuario</option>
                                                     
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="coment">Comentario</label>
                        <input type="text" name="coment" id="coment" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
                <hr>
                <h6>Dispositivos Asignados</h6>
                <ul id="user-devices">
                    <li>Seleccione un usuario para ver los dispositivos asignados.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>   
var table; 
$(document).ready(function() {
       table = $('#tb-invent').DataTable({
        ajax: {
            url: "{{ route('api.devices') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'tipodevice.name' },
            { data: 'name'}, // Asumiendo que type es una relación
            { data: 'departamento.name' ,
                render: function(data, type, row) {
                    return data ? data : ''; // Mostrar cadena vacía si no hay departamento
                }
            }, // Asumiendo que user es una relación
            { data: 'usuario.name',
                render: function(data, type, row) {
                    return data ? data : ''; // Mostrar cadena vacía si no hay usuario
                }
            },
            { data: 'sucursal.name' },
            { data: 'statusdevice.name' },
            {   data: null,
                className: "center",
                defaultContent: '',
                render: function(data, type, row) {
                    // Variables para controlar la visibilidad de los botones
                    var asignarButton = '';
                    var manttoButton = '';
                    
                    if (row.statusdevice.name === 'EN REPARACION'){
                        asignarButton = `
                        <button class="btn btn-success add_mantto" data-user_id="${row.user_id}" data-device_id="${row.id}" data-inventory_id="${row.inventory_id ? row.inventory_id: ''}">                                
                            <i class="fas fa-tools"></i>
                        </button>`;
                    }
                    // Condición para mostrar el botón de mantenimiento si el equipo está en reparación
                    if (row.statusdevice.name === 'EN STOCK') {
                        manttoButton = `
                            <button type="button" class="btn btn-warning asignar-device" data-toggle="modal" data-target="#modal-asignar-device" 
                            data-device-id="${row.id}" data-device-name="${row.name}" data-device-tipodevice="${row.tipodevice.name}">Asignar</button>`;
                    }
                    return `
                    ${manttoButton}
                    ${asignarButton}
                    <a href="device/${row.id}/edit" class="btn btn-info">Editar <i class='fas fa-edit'></i></a>                        
                    <form action="/device/${row.id}" method="post" class="d-inline delete-device-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar <i class='fas fa-eraser'></i></button>
                    </form>                                        
                    `;
                }
            },
        ],
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
            responsive: true,
            "autoWidth": true
    });

    // Objeto para almacenar los comentarios de cada dispositivo
    var deviceComments = {};

    $('#modal-asignar-device').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var deviceId = button.data('device-id'); // Extrae la información de atributos de datos
        var deviceName = button.data('device-name'); //
        var deviceTipodevice = button.data('device-tipodevice');
                
        var modal = $(this);

        // Guardar el comentario actual si existe
        var currentDeviceId = modal.find('#device-id').val();
        var currentComment = modal.find('#coment').val();
        if (currentDeviceId) {
            deviceComments[currentDeviceId] = currentComment;
        }

        // Resetear el formulario
        modal.find('form')[0].reset();

        // Setear valores específicos
        modal.find('#device-id').val(deviceId);
        modal.find('#device-name').text(deviceName); 
        modal.find('#device-tipodevice').text(deviceTipodevice);

        // Rellenar el comentario si existe para el dispositivo seleccionado
        if (deviceComments[deviceId]) {
            modal.find('#coment').val(deviceComments[deviceId]);
        }

        // Limpia la lista de dispositivos asignados
        var devicesList = modal.find('#user-devices');
        devicesList.empty();
        devicesList.append('<li>Seleccione un usuario para ver los dispositivos asignados.</li>');

        $.ajax({
            url: "{{ route('users.list') }}",
            method: 'GET',
            success: function(data) {
                var userSelect = modal.find('#user_id');
                userSelect.empty(); // Limpia el select
                userSelect.append('<option value="">Seleccione un usuario</option>');
                
                // Llenar el select con los usuarios
                $.each(data, function(index, user) {
                    userSelect.append('<option value="' + user.id + '">' + user.name + '</option>');
                });
            }
        });
        // Manejar el cambio de selección del usuario
        modal.find('#user_id').on('change', function() {
            var userId = $(this).val();
            if (userId) {
                // Solicitar los dispositivos del usuario
                $.ajax({
                    url: '/user/' + userId + '/devices',
                    method: 'GET',
                    success: function(data) {
                        var devicesList = modal.find('#user-devices');
                        devicesList.empty(); // Limpia la lista de dispositivos

                        if (data.length > 0) {
                            $.each(data, function(index, device) {
                                devicesList.append('<li>' + device.tipodevice.name +':  '+ device.name +'</li>');
                            });
                        } else {
                            devicesList.append('<li>No tiene dispositivos asignados.</li>');
                        }
                    }
                });
            } else {
                modal.find('#user-devices').empty();
                devicesList.append('<li>Seleccione un usuario para ver los dispositivos asignados.</li>');
            }
        });
    });
        // Manejar el envío del formulario
    $('#modal-asignar-device form').on('submit', function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        var form = $(this);
        var actionUrl = form.attr('action');
        var formData = form.serialize();

        $.ajax({
            url: "{{ route('inventory.store') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                // Mostrar un mensaje de éxito o manejar la respuesta
                // alert('El dispositivo ha sido asignado al usuario correctamente.');
                $('#modal-asignar-device').modal('hide'); // Cerrar el modal

                // Recargar los datos de la DataTable
                table.ajax.reload(null, false);

                // Resetear el modal
                form[0].reset();
                $('#user-devices').empty().append('<li>Seleccione un usuario para ver los dispositivos asignados.</li>')
            },
            error: function(xhr, status, error) {
                // Manejar los errores
                alert('Ocurrió un error al asignar el dispositivo. Por favor, inténtelo de nuevo.');
            }
        });
    });
    // Manejar la eliminación del dispositivo
    $(document).on('submit', '.delete-device-form', function(event) {
        event.preventDefault();

        if (!confirm('¿Está seguro de que desea eliminar este dispositivo?')) {
            return;
        }

        var form = $(this);
        var actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                alert('Dispositivo eliminado correctamente.');
                table.ajax.reload(null, false);
            },
            error: function(xhr, status, error) {
                alert('Ocurrió un error al eliminar el dispositivo. Por favor, inténtelo de nuevo.');
            }
        });
    });
});  

</script>


@endsection










