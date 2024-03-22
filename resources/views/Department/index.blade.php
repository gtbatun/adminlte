@extends('adminlte::page')
@section('content')
<h1>index department</h1>

<div class="container">
    <div class="col-12 mt-4">
       
        <div>
            <a href="{{route('department.create')}}" class="btn btn-primary">Crear Departamento</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>

        @endif

    
      @isset($departments)  
       <div class="col-16 mt-4">
        <table class="table table-bordered text-black">
            <tr class="text-secondary">
                <!-- <th>ID</th> -->
                <th>Departamento</th>
                <th>Descripcion</th>
                <th>Acción</th>
            </tr>
            
            @foreach($departments as $department)
            <tr>
                
                <td class="fw-bold">{{$department->name}}</td>
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
        </table>
        {{$departments->links()}}
    </div>
    @else
    <p>No hay areas creadas</p>
    @endisset
   
</div>
@endsection