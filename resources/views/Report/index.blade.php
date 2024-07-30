@extends('adminlte::page')
@section('content') 
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/moment.min.js')}}"></script>
<div class="container-fluid" >

    <form id="reportForm" method="GET" action="{{ route('report.search') }}">
        @csrf
        <div class="container d-flex justify-content-between" >
        <div class="col-4 mb-1">
        <label class="form-label" for="start_date">Fecha Inicio:</label>
        <input class="form-control" type="date" id="start_date" name="start_date" required>
        </div>
        <div class="col-4 mb-1">
        <label class="form-label" for="end_date">Fecha Fin:</label>
        <input class="form-control" type="date" id="end_date" name="end_date" required>
        </div>
        <div class="col-4 mb-3">
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
                    <thead>
                        <tr> 
                            <th>ID</th>
                            <th>Sucursal</th>
                            <th>Creado por</th>
                            <th>Asignado a</th>
                            <th>Area</th>
                            <th>Categoría</th>
                            <th>Título</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Atendio</th>
                        </tr>
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
        var table = $('#reportTableone').DataTable({
            "language": {
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                "info": "Mostrando _START_ de _END_ de _TOTAL_ ",
                "infoFiltered": "(filtrado de un total de _MAX_)",
                "emptyTable": "Sin Datos a Mostrar",
                "zeroRecords": "No se encontraron coincidencias",
                "infoEmpty": "Mostrando 0 de 0 de 0 coincidencias",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "first": "Primero",
                    "last": "Ultimo"
                }
            }
        });

        $('#reportForm').on('submit', function(e) {
            e.preventDefault();

            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#tableContainer').show();
                    $('#exportExcel').show();
                    table.clear().draw();
                    response.data.forEach(function(ticket) {                            
                        table.row.add([
                            ticket.id,
                            ticket.user_sucursal ? ticket.user_sucursal : '',
                            // ticket.creator_department ? ticket.creator_department.name : '',
                            ticket.creador,
                            // ticket.assigned_department ? ticket.assigned_department.name : '',
                            ticket.asignado,
                            // ticket.area ? ticket.area.name : '',
                            ticket.concepto,
                            // ticket.category ? ticket.category.name : '',
                            ticket.categoria,
                            ticket.title,
                            // ticket.created_at,
                            moment(ticket.created_at).format('YYYY-MM-DD'), // Formatear la fecha
                            // ticket.status ? ticket.status.name : '',
                            ticket.estado,
                            ticket.personal_sistemas ? ticket.personal_sistemas : ''
                        ]).draw(false);
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
        // console.log(startDate, endDate);
        var url = "{{ route('reporte.excel', ['start_date' => ':startDate', 'end_date' => ':endDate']) }}";
        url = url.replace(':startDate', startDate).replace(':endDate', endDate);
        // console.log(url); // Verifica que la URL sea correcta
        window.location.href = url;

    });

</script>

@endsection