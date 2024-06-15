@extends('adminlte::page')
@section('content')
<div class="container-fluid">
    <div class="col-12 mt-1">       
        <div>
            <a href="{{route('department.create')}}" class="btn btn-primary">Crear Departamento</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-1">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
  
    @isset($departments)  
       <div class="col-12 mt-1">
            <div class="card fluid">
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- <table id="tickets_clo1"  class="table table-bordered text-black shadow-lg mt-2"> -->
                        <table id="tb-deps" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr class="text-secondary">
                                    <th>ID</th>
                                    <th>Sucursal</th>
                                    <th>Departamento</th>
                                    <th>Tickets</th>
                                    <th>Descripcion</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>                        
                                @foreach($departments as $department)
                                <tr>
                                    <td class="fw-bold">{{$department->id}}</td>
                                    <td class="fw-bold">
                                        @if(!empty($department->sucursal_names))
                                            @foreach($department->sucursal_names as $sucursal)
                                                {{ $sucursal }}@if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            No asignadas
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{$department->name}}</td>
                                    <td>
                                        @if(!empty($department->enableforticket))
                                            Acepta tickets
                                        @else
                                            No Acepta
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{$department->description}}</td>
                                    <td>
                                        <a href="{{route('department.edit',$department)}}" class="btn btn-warning">Editar</a>

                                        <form action="{{route('department.destroy',$department)}}" method="post" class="d-inline">
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
        </div>
    @else
    <p>No hay Departamentos creados</p>
    @endisset
</div>
@endsection

@section('js')
<script>  
$(document).ready(function() {
    $('#tb-deps').DataTable({
        responsive: true,
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
    });
} );
</script>

@endsection