
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
            <form >
                @csrf
                <div class="form-group">
                    <label for="">inventory_id</label>
                    <input type="text" id="mantto-inventory-id">
                </div>
                <div class="form-group">
                    <label for="">device_id</label>
                    <input type="text" id="mantto-device-id">
                </div>
                <div class="form-group">
                    <label for="">usuario que hace el mantto</label>
                    <input type="text" id="usermantto_id" value="{{auth()->user()->id}}">
                </div>
                <div class="form-group">
                    <label for="">usuario del equipo</label>
                    <input type="text" id="user_id">
                </div>
                <div class="form-group">
                    <label for="mantto-comment">Comentario:</label>
                    <textarea id="mantto_comment" class="form-control" rows="3"></textarea>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> -->
                    <button type="button" class="btn btn-success" id="add-mantto">Guardar</button>
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
        $('#mantto-comment').val(''); // Resetea el comentario 
        $('#manttodeviceModal').modal('show');
    });

    $('#add-mantto').on('click', function() {
        const mantto_comment = $('#mantto_comment').val();
        if (mantto_comment) {
            $.ajax({
                url: "{{route('mantto.store')}}",
                type: 'POST',
                data: {
                    mantto_inventory_id: $('#mantto-inventory-id').val(),
                    mantto_device_id: $('#mantto-device-id').val(),
                    usermantto_id: $('#usermantto_id').val(),
                    user_id: $('#user_id').val(),
                    mantto_comment: mantto_comment
                },
                success: function(response) {
                    // console.log(response);
                    console.log('Success:', response);
                    // $('#manttodeviceModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al agregar el mantenimiento del dispositivo. Por favor, inténtelo de nuevo.');
                }
            });
        } else {
            console.log('deleteComment: ' + mantto_comment);
            alert('Por favor, ingrese un comentario.');
        }
    });
</script>