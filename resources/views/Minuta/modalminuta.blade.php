<div class="modal" id="modal-minuta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formato de solicitud de informacion y capacitacion <strong class="text-danger"> <br><span id="ticket-name-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <!-- action="{{route('ticket.agendarcita')}}"  method="post" -->
                <form id="agendarcita" >
                    @csrf
                    <input type="hidden" name="ticket_id" id="ticket-id">
                    <input type="hidden" name="departmentOld_id" id="ticket-department">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">                 

                    <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
                    <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Descripción de la consulta o capacitacion solicitada:</strong>
                            <textarea class="form-control" style="height:80px" name="respuesta" placeholder="Descripción...">{{$ticket->description}}</textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Respuesta y solucion brindada:</strong>
                            <textarea class="form-control" style="height:80px" name="respuesta" placeholder="Descripción...">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque fugiat doloremque consequuntur accusamus velit debitis similique hic nisi repudiandae commodi officiis culpa rem magni, quis animi deleniti perferendis! Praesentium, quibusdam.</textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Acciones a realizar y responsable de realizar:</strong>
                            <textarea class="form-control" style="height:80px" name="acciones" placeholder="Descripción...">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque fugiat doloremque consequuntur accusamus velit debitis similique hic nisi repudiandae commodi officiis culpa rem magni, quis animi deleniti perferendis! Praesentium, quibusdam.</textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <div class="form-group">
                            <strong>Seguimiento o verificacion:</strong>
                            <textarea class="form-control" style="height:80px" name="seguimiento" placeholder="Descripción...">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque fugiat doloremque consequuntur accusamus velit debitis similique hic nisi repudiandae commodi officiis culpa rem magni, quis animi deleniti perferendis! Praesentium, quibusdam.</textarea>
                        </div>
                    </div>
                    <!-- <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque fugiat doloremque consequuntur accusamus velit debitis similique hic nisi repudiandae commodi officiis culpa rem magni, quis animi deleniti perferendis! Praesentium, quibusdam.</h1> -->

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                        <label class="form-label">Confirmacion de recepcion y conformidad con la informacion:</label>
                        <div>
                            <input type="radio" name="confirmacion" value="Si" id="confirmacion_si" onclick="toggleInput('terminos', false)">
                            <label for="terminos_si">Sí</label>

                            <input type="radio" name="confirmacion" value="No" id="confirmacion_no" onclick="toggleInput('terminos', true)">
                            <label for="terminos_no">No</label>
                        </div>

                        <input type="text" name="confirmacion_motivo" id="confirmacion_motivo" class="form-control mt-2 d-none" placeholder="Especificar motivo">
                    </div>
                    </div>

                    <button type="submit" id="submit-agendarcita" class="btn btn-primary mt-3">Agendar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Manejar el clic en el botón para abrir el modal
        $(document).on('click', '.modal-minuta', function() {
            var ticketDepartment = $(this).data('ticket-department');
            var ticketTitle = $(this).data('ticket-title');
            var ticketId = $(this).data('ticket-id');

            $('#modal-minuta').find('#ticket-id').val(ticketId);
            $('#modal-minuta').find('#ticket-name-title').text(ticketTitle);
            $('#modal-minuta').find('#ticket-department').val(ticketDepartment);

            $('#modal-minuta').modal('show');
        });

        // Enviar el formulario mediante AJAX
        $('#agendarcita').submit(function (event) {
            event.preventDefault(); // Evitar recarga de página

            $.ajax({
                url: "{{ route('ticket.agendarcita') }}",
                type: "POST",
                data: $(this).serialize(), // Serializar los datos del formulario
                success: function (response) {
                    console.log("Cita agendada:", response);

                    // Cerrar el modal
                    $('#modal-agendarcita').modal('hide');

                    //  Limpiar el formulario
                    $('#minuta')[0].reset();

                     //Recargar solo la tabla de tickets sin refrescar la página
                    if (table) {
                        table.ajax.reload(null, false); // Recarga los datos sin perder la paginación
                    }

                     // Solo recargar la tabla si existe
                    // if ($.fn.DataTable.isDataTable('#tickets-table')) {
                    //     $('#tickets-table').DataTable().ajax.reload(null, false);
                    // }

                   
                },
                error: function (xhr) {
                    console.error("Error al agendar la cita:", xhr.responseText);

                    // Mostrar mensaje de error
                    alert("Error al agendar la cita. Inténtelo de nuevo.");
                }
            });
        });

        //  Limpiar el formulario al cerrar el modal
        $('#modal-minuta').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });
    });
</script>
