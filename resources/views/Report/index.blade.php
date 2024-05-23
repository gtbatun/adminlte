@extends('adminlte::page')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="container">

<form class="bg-white py-3 px-4 shadow rounded"  method="post" id="form-generar-reporte" >

    @csrf
    <div class="container d-flex justify-content-between">
        <div class="col-4 mb-1">
        <label class="form-label" for="fecha_inicio">Fecha de inicio:</label>
        <input class="form-control" type="date" name="fecha_inicio">
        </div>
        <div class="col-4 mb-1">    
        <label class="form-label" for="fecha_fin">Fecha de fin:</label>
        <input class="form-control" type="date" name="fecha_fin">
        </div>
        <div class="col-4 mt-3">    
        <button class="btn btn-outline-primary  mt-3" type="submit">Buscar<i class="fas fa-search" style="font-size:24px"></i></button>
                
        </div>
        
    </div> 
</form>

   
<div id="previsualizacion"></div>
</div>




<script>
    document.getElementById('form-generar-reporte').addEventListener('submit', function(event){
        event.preventDefault();
        var formData = new FormData(this);
        
        fetch("{{ route('reportes.generar') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        // .then(response => response.json())
        .then(data => {
            document.getElementById('previsualizacion').innerHTML = data;
            $('#tabla-reportes').DataTable({
                button:[{extend: 'excelHtml', text: 'excel', className:'btn btn-success'}],
                scrollY:450,
                // paging:true,
                "language": {
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                "info":"Mostrando _START_ de _END_ de _TOTAL_ ",
                // "info":"Mostrando pagina _PAGE_ de _PAGES_ ",
                "infoFiltered":   "( filtrado de un total de _MAX_)",
                "emptyTable":     "Sin Datos a Mostrar",
                "zeroRecords":    "No se encontraron coincidencias",
                "infoEmpty":      "Mostrando 0 de 0 de 0 coincidencias",
                "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Ultimo",
                        },
            }
            }); // Inicializa DataTables en la tabla generada
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

@endsection