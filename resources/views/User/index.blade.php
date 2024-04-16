@extends('adminlte::page')
@section('content')
<div >

    
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

    <!-- <h1>
    Tickets
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-category">
        Crear
    </button>
    </h1> -->
    <!-- {{$users}} -->

    
    @isset($users)  
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card fluid">
                <!-- <div class="card-header d-flex justify-content-between"> -->
                    <!-- <h3 class="card-title">Listado de Tickets</h3> -->
                <!-- </div> -->
            <!-- /.card-header -->
            <div class="card-body ">
                <table id="categories" class="table table-bordered shadow-lg mt-4 
                table-striped  ">
                    <thead  class="table-dark ">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>EMAIL</th>
                            <th>DEPARTAMENTO</th>
                            <th>FECHA DE CREACION</th>
                            <th>ACCION</th>
                        </tr>
                    </thead>
                    @foreach($users as $userItem)                    
                    <tr>
                <td>{{$userItem->id}}</td>  
                <td>{{$userItem->name}}</td>
                <td>{{$userItem->email}}</td>
                <td>Soporte</td>               
                <td>{{$userItem->created_at->diffForHumans(null, false, false, 1)}}</td>             
                <td>
                <a href="{{route('user.edit',$userItem)}}" class="btn btn-warning">Editar <i class='fas fa-edit'></i></a>
                    <form action="{{route('user.destroy',$userItem)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar <i class='fas fa-eraser'></i></button>
                    </form>
                </td>
                
                
            </tr>          
                   
                    @endforeach
                </table>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
  
       
    @else
    <p>No hay Usuarios creados</p>
   @endisset
</div>





@endsection

