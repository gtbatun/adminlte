@extends('adminlte::page')
@section('content')
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
        <div class="col-4 mt-3">
        <button class="btn btn-outline-primary  mt-3" type="submit">Buscar</button>
        </div>
        </div>
    </form>
    
    <div class="col-12 mt-1">
        <div class="card fluid">
            <div class="card-body">  
                <div class="table-responsive" >
                    <table id="reportTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Creador</th>
                                <th>Asignado</th>
                                <th>Concepto</th>
                                <th>Categoría</th>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Personal Sistemas</th>
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
                        var table = $('#reportTable').DataTable();
                        table.clear().draw();
                        console.log(response);
                        response.data.forEach(function(ticket) {
                            table.row.add([
                                ticket.id,
                                ticket.creador,
                                ticket.department_id,
                                ticket.concepto,
                                ticket.categoria,
                                ticket.title,
                                ticket.fecha,
                                ticket.estado,
                                ticket.personal_sistemas
                            ]).draw(false);
                        });
                    }
                });
            });

            $('#reportTable').DataTable({
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
                    },
                }  
            });
        });
    </script>
@endsection