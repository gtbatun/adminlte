@extends('adminlte::page')
@section('content')

<div class="row">
    <div class="col-12 mt-1">       
        <div>
            <a href="{{route('category.create')}}" class="btn btn-primary">Crear Categoria</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    
    @isset($categories)  
    <div class="col-12 mt-1">
        <div class="card fluid">
            <div class="card-body">
                <table id="tb-cat" class="table table-bordered text-black">
                    <thead>
                    <tr class="text-secondary">
                        <th>ID</th>
                        <th>Categoria</th>
                        <th>Descripcion</th>
                        <th>Area</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                    <tr>                
                        <td class="fw-bold">{{$category->id}}</td>
                        <td class="fw-bold">{{$category->name}}</td>
                        <td class="fw-bold">{{$category->description}}</td>
                        <td class="fw-bold">{{$category->area->name}}</td>
                        <td class="d-flex justify-content-center  align-items-center">
                            <a href="{{route('category.edit',$category)}}" class="btn btn-warning mr-1">Editar</a>

                            <form action="{{route('category.destroy',$category)}}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>                
                    </tr>            
                    @endforeach
                    </tbody>            
                </table>        
            </div>
        </div>
    </div>
    @else
    <p>No hay areas creadas</p>
    @endisset   
</div>
@endsection

@section('js')
<script>
    
$(document).ready(function() {
    $('#tb-cat').DataTable({
        "order": [[ 0,"desc" ]],
        "language": {
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                "info":"Mostrando _START_ de _END_ de _TOTAL_ ",
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
    } );
} );
</script>
@endsection