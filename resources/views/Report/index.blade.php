<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
@extends('adminlte::page')
@section('content')

<form class="bg-white py-3 px-4 shadow rounded" action="{{route('ticket-export1') }}" method="post" id="form-generar-reporte" >
<!-- <form class="bg-white py-3 px-4 shadow rounded" action="{{ route('reportes.generar') }}" method="post" id="form-generar-reporte" > -->

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
        <div class="col-4 mt-4">    
        <button class="btn btn-outline-primary " type="submit"><i class="fa fa-search" style="font-size:24px"></i></button>
        <!-- <button class="btn btn-outline-primary " name="action" value="exportar" type="submit"><i class="fas fa-download" style="font-size:24px"></i></button> -->
            
        <!-- <button class="btn btn-outline-primary " type="submit"><i class="fa fa-search" style="font-size:24px">exportar</i></button> -->
        
        </div>
        <!-- <div class="col-md-6 text-right"> -->
        <!-- Botón de exportar reporte -->
        
    <!-- </div> -->
    </div> 
</form>

    <div  id="previsualizacion"></div>





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
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

@endsection