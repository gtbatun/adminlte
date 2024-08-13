<script src="{{asset('assets/js/plugins/moment.min.js')}}"></script>
<!-- Modal para mantenimiento -->
<div class="modal fade" id="manttodeviceModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manttodeviceModal">Mantenimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            <form id="mantto-form">
                @csrf
                <div class="form-group">
                    <!-- <label for="">inventory_id</label> -->
                    <input type="hidden" id="mantto-inventory-id">
                </div>
                <div class="form-group">
                    <!-- <label for="">device_id</label> -->
                    <input type="hidden" id="mantto-device-id">
                </div>
                <div class="form-group">
                    <!-- <label for="">usuario que hace el mantto</label> -->
                    <input type="hidden" id="usermantto_id" value="{{auth()->user()->id}}">
                </div>
                <div class="form-group">
                    <!-- <label for="">usuario del equipo</label> -->
                    <input type="hidden" id="user_id">
                </div>
                <div class="form-group">
                    <label for="mantto_task">Seleccione la Actividad</label>
                    <select id="mantto_task" class="form-control">
                    </select>
                </div>
                <div class="form-group">
                    <label for="mantto_status">Seleccionar Estado:</label>
                    <select id="mantto_status" class="form-control" >
                    <!-- Las opciones se llenarán dinámicamente -->   	
                    </select>
                </div> 
                <div class="form-group">
                    <label for="mantto-comment">Comentario:</label>
                    <textarea id="mantto_comment" class="form-control" rows="3"></textarea>
                </div>
            </form>
                <hr>
                <h6>Manttos realizados</h6>
                <ul id="mantto_device">
                    <!-- <li>Seleccione un equipo para ver los manttos realizados.</li> -->
                </ul>
                <hr>
                <h6>Rotaciones del Equipo</h6>
                <ul id="hist_device">
                    <!-- <li>Seleccione un equipo para ver los manttos realizados.</li> -->
                </ul>
            </div>
            <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> -->
                    <button type="button" class="btn btn-success" id="save_mantto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.add_mantto', function() {
        deviceIdTomantto= $(this).data('device_id');
        inventoryIdTomantto = $(this).data('inventory_id');
        userId = $(this).data('user_id');

        $('#user_id').val(userId);
        $('#mantto-device-id').val(deviceIdTomantto); 
        $('#mantto-inventory-id').val(inventoryIdTomantto);         
        $('#mantto_comment').val(''); // Resetea el comentario 
        $('#manttodeviceModal').modal('show');

        // Agregar y seleccionar un task mantenimiento, reparacion, actualizacion
        $.ajax({
            url: '/tasks',
            method: 'GET',
            success: function(data) {
                let taskSelect = $('#mantto_task');
                taskSelect.empty(); // Limpia el select
                data.forEach(task => {
                    taskSelect.append(`<option value="${task.id}">${task.name}</option>`);
                });
            }
        });
        // Rellenar el select de estados
        $.ajax({
            url: '/statuses',
            method: 'GET',
            success: function(data) {
                let statusSelect = $('#mantto_status');
                statusSelect.empty(); // Limpia el select
                data.forEach(status => {
                    statusSelect.append(`<option value="${status.id}">${status.name}</option>`);
                });
            }
        });


        $.ajax({
            url: '/device/' + deviceIdTomantto + '/tasks-and-assignments',
            method: 'GET',
            success: function(data) {
                // Manejar los tasks
                let ManttosdevicesList = $('#mantto_device');
                ManttosdevicesList.empty(); // Limpia el select
                data.tasks.forEach(task => {
                    // Formatear la fecha con Moment.js
                    let formattedDate = moment(task.created_at).format('YYYY-MM-DD');
                    ManttosdevicesList.append(`<li value="${task.id}">${formattedDate} ${task.coment}</li>`);
                });

                // Manejar los assignments
                let His_devicesList = $('#hist_device');
                His_devicesList.empty(); // Limpia el select
                if (data.assignments.length > 0) {
                    data.assignments.forEach(assignment => {
                        // Formatear la fecha con Moment.js
                        let formattedDate = moment(assignment.created_at).format('YYYY-MM-DD');
                        His_devicesList.append(`<li value="${assignment.id}">${formattedDate} ${assignment.coment}</li>`);
                    });
                } else {
                    devicesList.append('<li>No hay manttos realizados para este dispositivo.</li>');
                }
            }
        });


    });

    $('#save_mantto').on('click', function() {
        const mantto_comment = $('#mantto_comment').val();
        const mantto_task_id = $('#mantto_task').val();
        const mantto_status_id = $('#mantto_status').val();
        if (mantto_comment) {
            $.ajax({
                url: "{{ route('mantto.store') }}",
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    mantto_inventory_id: $('#mantto-inventory-id').val(),
                    mantto_device_id: $('#mantto-device-id').val(),
                    usermantto_id: $('#usermantto_id').val(),
                    user_id: $('#user_id').val(),
                    mantto_comment: mantto_comment,
                    mantto_task_id: mantto_task_id,
                    mantto_status_id : mantto_status_id
                },
                success: function(response) {
                    // console.log('Success:', response);
                    $('#manttodeviceModal').modal('hide');
                    // Verificar si la tabla está definida y recargarla
                    if (typeof table !== 'undefined' && table.ajax) {
                        console.log('Reloading table...');
                        table.ajax.reload(null, false);
                    }else {
                        console.log('Table is not defined or does not have ajax.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al agregar el mantenimiento del dispositivo. Por favor, inténtelo de nuevo.');
                    console.error('Error:', error);
                }
            });
        } else {
            // console.log('deleteComment: ' + mantto_comment);
            alert('Por favor, ingrese un comentario.');
        }
    });
</script>