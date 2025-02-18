<div class="modal" id="modal-editar-cita">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cita</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editarCitaForm">
                    @csrf
                    <input type="hidden" name="cita_id" id="edit-cita-id">
                    
                    <div class="form-group">
                        <strong>Título:</strong>
                        <input type="text" name="title" id="edit-cita-title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <strong>Fecha:</strong>
                        <input type="date" name="fecha" id="edit-fecha" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <strong>Hora:</strong>
                        <input type="time" name="hora" id="edit-hora" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
     eventDrop: function(info) {
            $.ajax({
                url: "{{ url('cita') }}/" + info.event.id,  // URL usando rutas resource
                method: "POST",  // Laravel espera POST con _method para actualizar
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",  // Laravel reconocerá la actualización
                    start_date: info.event.startStr,
                    end_date: info.event.endStr || info.event.startStr // Si no tiene end, usa start
                },
                success: function(response) {
                    alert("Evento actualizado correctamente.");
                },
                error: function(xhr) {
                    console.error("Error al actualizar la cita:", xhr.responseText);
                    alert("Error al actualizar la cita.");
                }
            });
        },

        eventClick: function(info) {
            let event = info.event;

            // Llenar los campos del modal
            $("#modal-editar-cita #edit-cita-id").val(event.id);
            $("#modal-editar-cita #edit-cita-title").val(event.title);
            $("#modal-editar-cita #edit-fecha").val(event.start.toISOString().split("T")[0]); // Fecha
            $("#modal-editar-cita #edit-hora").val(event.start.toTimeString().split(" ")[0]); // Hora

            $("#modal-editar-cita").modal("show");
        }
</script>