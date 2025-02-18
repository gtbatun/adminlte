@extends('adminlte::page')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<!-- FullCalendar CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.js"></script> -->


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
        initialView: 'timeGridWeek', // Mostrar por semana con horas
        // initialView: 'dayGridMonth',
        locale: 'es', // Español
        slotDuration: '00:30:00', // Intervalos de 30 minutos en la vista
        slotMinTime: '08:00:00', // Hora de inicio en el calendario
        slotMaxTime: '22:00:00', // Hora de fin en el calendario
        nowIndicator: true, // Indica la hora actual
        allDaySlot: false, // Oculta la opción de "todo el día"

        selectable: true,
        editable: true,
        // eventResizableFromStart: true,
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

        eventClick: function(info) {
            let event = info.event;

            // Llenar los campos del modal
            $("#modal-editar-cita #edit-cita-id").val(event.id);
            $("#modal-editar-cita #edit-cita-title").val(event.title);
            $("#modal-editar-cita #edit-fecha").val(event.start.toISOString().split("T")[0]); // Fecha
            $("#modal-editar-cita #edit-hora").val(event.start.toTimeString().split(" ")[0]); // Hora

            $("#modal-editar-cita").modal("show");
        }
    });

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
        success: function (response) {
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

            // Llamar a la función para cargar las horas al cargar la página

    $("#fecha").change(function () {
        // let fechaSeleccionada = $(this).val();
        let fechaSeleccionada = $(this).val();
        if (fechaSeleccionada) {
            cargarHorasDisponibles(fechaSeleccionada);
        }
    });

    }

    calendar.render();
});

</script>

<script>

            // Selección de eventos con duración mínima de 30 min
        // select: function(info) {
        //     var title = prompt("Ingrese el título del evento:");
        //     if (title) {
        //         $.ajax({
        //             url: "{{ route('cita.store') }}",
        //             method: "POST",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 title: title,
        //                 start_date: info.startStr,
        //                 // end_date: info.endStr,
        //                 end_date: info.endStr || moment(info.startStr).add(30, 'minutes').format() // Duración mínima 30 min
        //             },
        //             success: function(response) {
        //                 calendar.refetchEvents(); // Recargar eventos
        //                 alert("Evento creado correctamente.");
        //             }
        //         });
        //     }
        // },

        // Editar evento
        // eventDrop: function(info) {
        //     $.ajax({
        //         url: "{{url('cita/update')}}" + info.event.id,
        //         method: "POST",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             _method: "PUT", // Laravel detecta que es una actualización
        //             start_date: info.event.startStr,
        //             end_date: info.event.endStr
        //         },
        //         success: function(response) {
        //             alert("Evento actualizado.");
        //         }
        //     });
        // },




            // Eliminar evento
        // eventClick: function(info) {
        //     if (confirm("¿Estás seguro de eliminar este evento?")) {
        //         $.ajax({
        //             url: "{{ url('cita/') }}/" + info.event.id,
        //             method: "DELETE",
        //             data: { _token: "{{ csrf_token() }}" },
        //             success: function(response) {
        //                 calendar.refetchEvents(); // Recargar eventos después de eliminar
        //                 alert("Evento eliminado.");
        //             }
        //         });
        //     }
        // }

</script>
@endsection
