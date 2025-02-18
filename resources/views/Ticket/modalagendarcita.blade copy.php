<div class="modal" id="modal-agendarcita">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agendar cita <strong class="text-danger"> <br><span id="ticket-name-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <!-- action="{{route('ticket.agendarcita')}}"  method="post" -->
                <form id="agendarcita" >
                    @csrf
                    <input type="hidden" name="ticket_id" id="ticket-id">
                    <input type="hidden" name="department_id" id="ticket-department">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">                 

                    <!-- <div class="form-group">
                    <strong>Fecha:</strong>
                    <input type="date" name="fecha"  class="form-control" required >                    
                    </div>

                    <div class="form-group">
                    <strong>Hora:</strong>
                    <input type="time" name="hora" class="form-control" required>                    
                    </div> -->

                    <div class="form-group">
                        <strong>Fecha:</strong>
                        <input type="date" name="fecha" id="fecha" class="form-control" required min="{{ date('Y-m-d') }}">                    
                    </div>

                    <div class="form-group">
                        <strong>Hora:</strong>
                        <select name="hora" id="hora" class="form-control" required>
                            <option value="">Seleccione una hora</option>
                        </select>
                    </div>
                    <button type="submit" id="submit-agendarcita" class="btn btn-primary mt-3">Agendar</button>
                </form>                 
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
    // Función para llenar el select de horas con intervalos de 30 minutos
    function cargarHorasDisponibles() {
        let horaInicio = 8; // 08:00 AM
        let horaFin = 18; // 06:00 PM
        let selectHora = $("#hora");

        selectHora.empty(); // Limpiar opciones
        selectHora.append('<option value="">Seleccione una hora</option>');

        for (let h = horaInicio; h < horaFin; h++) {
            selectHora.append(`<option value="${h}:00">${h}:00</option>`);
            selectHora.append(`<option value="${h}:30">${h}:30</option>`);
        }
    }

    // Llamar a la función para cargar las horas al cargar la página
    cargarHorasDisponibles();

    // Evitar selección de fechas pasadas
    let today = new Date().toISOString().split('T')[0];
    $("#fecha").attr("min", today);

    // Manejar el clic en el botón para abrir el modal
    $(document).on("click", ".modal-agendarcita", function() {
        let ticketDepartment = $(this).data("ticket-department");
        let ticketTitle = $(this).data("ticket-title");
        let ticketId = $(this).data("ticket-id");

        $("#modal-agendarcita #ticket-id").val(ticketId);
        $("#modal-agendarcita #ticket-name-title").text(ticketTitle);
        $("#modal-agendarcita #ticket-department").val(ticketDepartment);

        $("#modal-agendarcita").modal("show");
    });

    // Validar antes de enviar el formulario
    $("#agendarcita").submit(function (event) {
        event.preventDefault(); // Evitar recarga de página

        let fecha = $("#fecha").val();
        let hora = $("#hora").val();

        if (!fecha || !hora) {
            alert("Debe seleccionar una fecha y hora válidas.");
            return;
        }
// ticket.agendarcita'
        $.ajax({
            url: "{{ route('cita.store') }}",
            type: "POST",
            data: $(this).serialize(), // Serializar los datos del formulario
            success: function (response) {
                console.log("Cita agendada:", response);
                alert("Cita agendada correctamente.");

                // Cerrar el modal
                $("#modal-agendarcita").modal("hide");

                // Limpiar el formulario
                $("#agendarcita")[0].reset();

                // Recargar la tabla de tickets sin recargar la página
                if (typeof table !== "undefined") {
                    table.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                console.error("Error al agendar la cita:", xhr.responseText);
                alert("Error al agendar la cita. Inténtelo de nuevo.");
            }
        });
    });

    // Limpiar el formulario al cerrar el modal
    $("#modal-agendarcita").on("hidden.bs.modal", function () {
        $(this).find("form")[0].reset();
    });
});

</script>


