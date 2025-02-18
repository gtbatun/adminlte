@extends('adminlte::page')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<div class="container p-3">
    <div class="card p-2">
        <div id="calendar"></div>
    </div>
</div>

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
                            <select name="hora" id="hora" class="form-control" required >
                            <option value="">Seleccione una hora</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')


<!-- FullCalendar Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es', // Español
        slotDuration: '00:30:00', // Intervalos de 30 minutos en la vista
        slotMinTime: '08:00:00', // Hora de inicio en el calendario
        slotMaxTime: '22:00:00', // Hora de fin en el calendario
        nowIndicator: true, // Indica la hora actual
        allDaySlot: false, // Oculta la opción de "todo el día"
        selectable: true,
        editable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {
            url: "{{ route('cita.citasagendadas') }}", // Cargar eventos desde la BD
            method: "GET",
            failure: function() {
                alert("No se pudieron cargar los eventos.");
            }
        },

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

        eventClick: function (info) {
            let event = info.event;

            // Llenar los campos del modal con la información del evento
            $("#modal-editar-cita #edit-cita-id").val(event.id);
            $("#modal-editar-cita #edit-cita-title").val(event.title);
            $("#modal-editar-cita #edit-fecha").val(event.start.toISOString().split("T")[0]);
            $("#modal-editar-cita").modal("show");

            // Llamar a la función para cargar los horarios disponibles
            cargarHorasDisponibles(event.start.toISOString().split("T")[0]);
        },

    });

    calendar.render();

    // Función para llenar el select de horas con intervalos de 30 minutos
    function cargarHorasDisponibles(fechaSeleccionada) {
    let horaInicio = 8; // 08:00 AM
    let horaFin = 18; // 06:00 PM
    let selectHora = $("#hora");

    selectHora.empty(); // Limpiar opciones
    selectHora.append('<option value="">Seleccione una hora</option>');

    $.ajax({
        url:"{{ route('cita.unablefech') }}",
        type: "GET",
        dataType: "json",
        data:{fecha: fechaSeleccionada},
        success: function (response) {

            if (!Array.isArray(response)) {
                console.error("Respuesta inesperada de la API:", response);
                return;
            }

            let horariosOcupados = response.map(cita => cita.start);

            for (let h = horaInicio; h < horaFin; h++ ){

                let horaCompleta = `${fechaSeleccionada} ${h.toString().padStart(2, '0')}:00:00`;
                let horaMedia = `${fechaSeleccionada} ${h.toString().padStart(2, '0')}:30:00`;

                let disabledFull = horariosOcupados.includes(horaCompleta);
                let disabledHalf = horariosOcupados.includes(horaMedia);

                selectHora.append(
                `<option value="${h.toString().padStart(2, '0')}:00" ${disabledFull ? "disabled" : ""}>${h}:00</option>`
                );
                selectHora.append(
                    `<option value="${h.toString().padStart(2, '0')}:30" ${disabledHalf ? "disabled" : ""}>${h}:30</option>`
                );
            }
        },
        error: function () {
        console.error("Error al obtener los horarios ocupados.");
    }
    });

}

// Función para guardar los cambios en la cita
$("#editarCitaForm").submit(function(event) {
        event.preventDefault();

        let citaId = $("#edit-cita-id").val();
        let title = $("#edit-cita-title").val();
        let fecha = $("#edit-fecha").val();
        let hora = $("#hora").val();

        if (!fecha || !hora) {
            alert("Debe seleccionar una fecha y una hora válida.");
            return;
        }

        let start_date = `${fecha} ${hora}`;
        let start_end = '';

        $.ajax({
            url: "{{ url('cita') }}/" + citaId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "PUT",
                title: title,
                start_date: start_date,
                // asign_end: true ,
            },
            success: function(response) {
                alert("Cita actualizada correctamente.");
                $("#modal-editar-cita").modal("hide");
                calendar.refetchEvents(); // Recargar eventos en el calendario
            },
            error: function(xhr) {
                console.error("Error al actualizar la cita:", xhr.responseText);
                alert("Error al actualizar la cita.");
            }
        });
    });



// Llamar a la función para cargar las horas al cargar la página

// $("#fecha").change(function () {
//     // let fechaSeleccionada = $(this).val();
//     let fechaSeleccionada = $(this).val();
//     if (fechaSeleccionada) {
//         cargarHorasDisponibles(fechaSeleccionada);
//     }
// });

// Ejecutar la función cuando se seleccione una fecha
$("#edit-fecha").change(function() {
    let fechaSeleccionada = $(this).val();
    if (fechaSeleccionada) {
        cargarHorasDisponibles(fechaSeleccionada);
    }
});

// Evitar selección de fechas pasadas
let today = new Date().toISOString().split('T')[0];
$("#edit-fecha").attr("min", today);

    
});

</script>
@endsection
