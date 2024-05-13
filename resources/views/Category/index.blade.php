@extends('adminlte::page')
@section('content')

<div class="row">
    <div class="col-12 mt-4">
       
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
       <div class="col-12 mt-4">
       <div class="card fluid">
            <div class="card-body">
        <table class="table table-bordered text-black">
            <tr class="text-secondary">
                <!-- <th>ID</th> -->
                <th>Categoria</th>
                <th>Descripcion</th>
                <th>Area</th>
                <th>Acción</th>
            </tr>
            
            @foreach($categories as $category)
            <tr>
                
                <td class="fw-bold">{{$category->name}}</td>
                <td class="fw-bold">{{$category->description}}</td>
                <td class="fw-bold">{{$category->area->name}}</td>
                <td>
                    <a href="{{route('category.edit',$category)}}" class="btn btn-warning">Editar</a>

                    <form action="{{route('category.destroy',$category)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
                
            </tr>
            
            @endforeach            
        </table>
        {{$categories->links()}}
    </div></div></div>
    @else
    <p>No hay areas creadas</p>
    @endisset
   
</div>
@endsection