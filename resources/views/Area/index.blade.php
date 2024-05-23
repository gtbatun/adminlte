
@extends('adminlte::page')
@section('content')

<div class="container">
    <div class="col-12 mt-4">
       
        <div>
            <a href="{{route('area.create')}}" class="btn btn-primary">Crear area</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif

    
      @isset($areas)  
       <div class="col-16 mt-4">
       <div class="card fluid">
            <div class="card-body">
        <table class="table table-bordered text-black">
            <tr class="text-secondary">
                <th>ID</th>
                <th>Departamento</th>
                <th>Area</th>
                <th>Descripcion</th>
                <th>Acción</th>
            </tr>
            
            @foreach($areas as $area)
            <tr>
                <td class="fw-bold">{{$area->id}}</td>
                <td class="fw-bold">{{$area->department->name}}</td>
                <td class="fw-bold">{{$area->name}}</td>
                <td class="fw-bold">{{$area->description}}</td>
                <td>
                    <a href="{{route('area.edit',$area)}}" class="btn btn-warning">Editar</a>

                    <form action="{{route('area.destroy',$area)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
                
            </tr>
            
            @endforeach            
        </table>
        {{$areas->links()}}
    </div>
    </div>
    </div>
    @else
    <p>No hay areas creadas</p>
    @endisset
   
</div>
@endsection