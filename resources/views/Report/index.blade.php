@extends('adminlte::page')
@section('content') 
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/moment.min.js')}}"></script>
<div class="container-fluid" >

    <form id="reportForm" method="GET" action="{{ route('report.search') }}">
        @csrf
        <div class="container d-flex justify-content-between" >
        <div class="col-3 mb-1">
            <label class="form-label" for="reporttype">De:</label>
            <select class="form-control" id="reporttype" name="reporttype">
            <option value="tickets">Tickets</option> 
            <option value="equipos">Equipos</option>             
            </select>
        </div>
        <div class="col-3 mb-1">
        <label class="form-label" for="start_date">Fecha Inicio:</label>
        <input class="form-control" type="date" id="start_date" name="start_date" required>
        </div>
        <div class="col-3 mb-1">
        <label class="form-label" for="end_date">Fecha Fin:</label>
        <input class="form-control" type="date" id="end_date" name="end_date" required>
        </div>
        <div class="col-3 mb-3">
        <button class="btn btn-outline-primary  mt-3" type="submit">Buscar</button>
        <button id="exportExcel" class="btn btn-primary mt-3" style="display: none;">Exportar a Excel</button>
        </div>
        </div>
    </form>
    <div class="col-12 mt-1 " id="tableContainer" style="display: none;">
        <div class="card fluid">
            <div class="card-body">  
                <div class="table-responsive" >
                <table id="reportTableone" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                    <thead id="reportTableHead">
                        <th>ID</th><!-- es necesario dejarlo, en caso contrario manda al json y marca error en la consola  -->
                    </thead>
                    <tbody>
                        <!-- Aquí se llenarán los datos con AJAX -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        var table = $('#reportTableone').DataTable();

        $('#reportForm').on('submit', function(e) {
            e.preventDefault();

            var reporttype = $('#reporttype').val(); // seleccionar el reporte que se desea seleccionar
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: {
                    reporttype: reporttype,
                    start_date: startDate,
                    end_date: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#tableContainer').show();
                    $('#exportExcel').show();

                    // Destruir el DataTable antes de modificar los encabezados
                    table.destroy();

                    // Limpiar los encabezados y el contenido de la tabla antes de agregar los nuevos
                    $('#reportTableHead').empty();
                    $('#reportTableone tbody').empty();
                    

                    if (reporttype === 'tickets') {
                        $('#reportTableHead').append(
                            '<tr><th>ID</th><th>Sucursal</th><th>Creado por</th><th>Asignado a</th><th>Area</th><th>Categoría</th><th>Título</th><th>Fecha</th><th>Estado</th><th>Atendio</th></tr>'
                        );
                        response.data.forEach(function(ticket) {
                        $('#reportTableone tbody').append('<tr><td>' + ticket.id + '</td><td>' + (ticket.user_sucursal ? ticket.user_sucursal : '') + '</td><td>' + ticket.creador + '</td><td>' + ticket.asignado + '</td><td>' + ticket.concepto + '</td><td>' + ticket.categoria + '</td><td>' + ticket.title + '</td><td>' + moment(ticket.created_at).format('YYYY-MM-DD') + '</td><td>' + ticket.estado + '</td><td>' + (ticket.personal_sistemas ? ticket.personal_sistemas : '') + '</td></tr>');
                    });
                    
                } else if (reporttype === 'equipos'){
                        $('#reportTableHead').append(
                            '<tr><th>ID</th><th>Nombre</th><th>Tipo</th></tr>'
                        );
                        response.data.forEach(function(equipo) {
                        $('#reportTableone tbody').append('<tr><td>' + equipo.id + '</td><td>' + equipo.name + '</td><td>' + equipo.description + '</td></tr>');
                        });
                    }
                    // Recrear el DataTable después de modificar los encabezados y el contenido
                    table = $('#reportTableone').DataTable({
                        "language": {
                            "search": "Buscar",
                            "lengthMenu": "Mostrar _MENU_ tickets por página",
                            "info": "Mostrando _START_ de _END_ de _TOTAL_",
                            "infoFiltered": "(filtrado de un total de _MAX_)",
                            "emptyTable": "Sin Datos a Mostrar",
                            "zeroRecords": "No se encontraron coincidencias",
                            "infoEmpty": "Mostrando 0 de 0 de 0 coincidencias",
                            "paginate": {
                                "previous": "Anterior",
                                "next": "Siguiente",
                                "first": "Primero",
                                "last": "Último"
                            }
                        }
                    });
                    
                },
                error: function(xhr) {
                    console.error('Error fetching data:', xhr);
                    alert('Error fetching data. Please check the logs for more details.');
                }
            });
        });
    });

    $('#exportExcel').on('click', function() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var url = "{{ route('reporte.excel', ['start_date' => ':startDate', 'end_date' => ':endDate']) }}";
        url = url.replace(':startDate', startDate).replace(':endDate', endDate);
        // console.log(url); // Verifica que la URL sea correcta
        window.location.href = url;

    });

</script>

@endsection