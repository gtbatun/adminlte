<!-- Modal --> 
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <div class="form-group">
          <h5 class="modal-title" id="exampleModalLabel">Formulario de Equipo</h5>        
          <h4 id="search-user-name1" value="" class="text-danger" ></h4>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createDeviceForm" method="POST">
          @csrf
          <input type="hidden" id="search-user-id" value="">
          <!-- <input type="text" id="search-user-name" value=""> -->
          <div class="form-group">
            <label for="tipo_equipo_id">Tipo de equipo</label>
            <select id="tipo_equipo" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="title">Hostname</label>
            <input class="form-control border-0 bg-light shadow-sm" type="text" id="name"  autocomplete="off">
          </div>
          <div class="form-group">
            <label for="title">Marca</label>
            <select id="marca" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="title">Serie</label>
            <input class="form-control border-0 bg-light shadow-sm" type="text" id="serie" >
          </div>
          <div class="form-group">
            <label for="title">Almacenamiento</label>
            <select id="almacenamiento" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="title">Procesador</label>
            <select id="procesador" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripción" id="description"></textarea>
          </div>
          <div class="form-group">
            <label for="sucursal_id">Sucursal</label>
            <select id="sucursal" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="statusdevice_id">Estatus</label>
            <select id="status" class="form-control">
            </select>
          </div>          
            <button type="button"  id="saveDevice" class="btn btn-primary btn-block">Guardar</button>
            <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cancelar</button>         
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  var userId;
    $('#createModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        userId = button.data('user-id'); // Extrae la información de data-user-id
        
        $('#search-user-id').val(userId);
        // $('#search-user-name').val(button.data('user-name'));
        $('#search-user-name1').text(button.data('user-name'));
        // console.log(userId);

        $.ajax({
            url: '/device-data',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                // Llenar los campos del modal con los datos obtenidos
                fillSelectOptions('#tipo_equipo', data.tipo_equipo);
                fillSelectOptions('#marca', data.marca);
                fillSelectOptions('#almacenamiento', data.almacenamiento);
                fillSelectOptions('#procesador', data.procesador);
                fillSelectOptions('#status', data.status);
                fillSelectOptions('#department', data.department);
                fillSelectOptions('#sucursal', data.sucursal);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    $('#createModal').on('hidden.bs.modal', function (event) {
        resetForm('#createDeviceForm');
        
    });
    function fillSelectOptions(selector, options) {
        var select = $(selector);
        select.empty();
        select.append('<option value="">Seleccione una opcion</option>');
        $.each(options, function(key, value) {
            select.append($('<option>', { value: key, text: value }));
        });
    }
    function resetForm(formId) {
        $(formId)[0].reset();
        $(formId + ' select').val('').trigger('change');
    }
    $('#saveDevice').click(function() {
        event.preventDefault();

        // Validar campos
        var isValid = true;
        var formData = {
            name:$('#name').val(),
            serie:$('#serie').val(),
            description:$('#description').val(),
            tipo_equipo_id: $('#tipo_equipo').val(),
            marca_id: $('#marca').val(),
            almacenamiento_id: $('#almacenamiento').val(),
            procesador_id: $('#procesador').val(),
            statusdevice_id: $('#status').val(),
            sucursal_id: $('#sucursal').val(),
            // user_id: $('#search-user-id').val(),
            _token: '{{ csrf_token() }}'
        };

        // Verifica si user_id tiene un valor antes de agregarlo a formData
        var userId = $('#search-user-id').val();
        if (userId) {
            formData.user_id = userId;
        }

        $.each(formData, function(key, value) {
            if (key !== '_token' && value === '') {
                isValid = false;
                $('#' + key).addClass('is-invalid');
            } else {
                $('#' + key).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            alert('Por favor, complete todos los campos obligatorios.');
            return;
        }

        $.ajax({
            url: "{{route('device.store')}}",
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                // $('#createModal').modal('hide');                        
                if(response.success) {
                    // alert(response.message);
                    $('#createModal').modal('hide');
                    // Refrescar la tabla de equipos después de guardar    
                    // $('#tb-invent').DataTable().ajax.reload();
                    // Aquí puedes agregar código para actualizar la tabla principal si es necesario 
                    loadUserDevices(userId);               
                } else {
                    alert('Error al crear el dispositivo.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });    
});
</script>

