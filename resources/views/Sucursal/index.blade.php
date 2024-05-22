@extends('adminlte::page')
@section('content')
<!-- <h1>index de status</h1> -->

<div class="container">
    <div class="col-12 mt-4">
       
        <div>
            <a href="{{route('sucursal.create')}}" class="btn btn-primary">Crear Sucursal</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>

        @endif

    
      @isset($sucursal)  
       <div class="col-16 mt-4">
       <div class="card fluid">
            <div class="card-body">
        <table class="table table-bordered text-black">
            <tr class="text-secondary">
                <!-- <th>ID</th> -->
                <th>Sucursal</th>
                <th>Descripcion</th>
                <th>Acción</th>
            </tr>
            
            @foreach($sucursal as $sucursalItem)
            <tr>
                
                <td class="fw-bold">{{$sucursalItem->name}}</td>
                <td class="fw-bold">{{$sucursalItem->description}}</td>
                <td>
                    <a href="{{route('sucursal.edit',$sucursalItem)}}" class="btn btn-warning">Editar</a>

                    <form action="{{route('sucursal.destroy',$sucursalItem)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
                
            </tr>
            
            @endforeach            
        </table>
        {{$sucursal->links()}}
    </div>
    </div>
    </div>
    @else
    <p>No hay Sucursales creadas</p>
    @endisset
   
</div>
@endsection