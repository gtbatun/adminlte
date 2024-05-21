<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@extends('adminlte::page')
@section('content')

<div >

    
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end m-2">
    <a href="{{route('user.create')}}" type="button" class="btn btn-primary p-2 ">Nuevo Usuario</a>
    </div>
    
    @isset($users)  
    <div class="container-fluid">


    <div class="row">
        <div class="col-12">
            <div class="card fluid">
                <!-- <div class="card-header d-flex justify-content-between"> 
                    <h3 class="card-title">Listado de Tickets</h3> 
                 </div> -->
            <!-- /.card-header -->
            <div class="card-body ">
                <table id="users" class="table table-bordered shadow-lg mt-2
                table-striped  ">
                    <thead  class="table-dark ">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>EMAIL</th>
                            <th>VERIFICADO</th>
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
                <td>
                    @if (is_null($userItem->email_verified_at))
                        <a href="{{ route('admin.verify-email', $userItem->id) }}" class="btn btn-primary">Verificar Correo</a>
                    @else
                        Verificado
                    @endif
                </td>
                @if(isset($userItem->department->name))
                    <td>{{$userItem->department->name }} </td>                    
                @else
                    <td> </td>
                @endif
                               
                <td>{{$userItem->created_at->diffForHumans(null, false, false, 1)}}</td>             
                <td>
                <a href="{{route('user.edit',$userItem)}}" class="btn btn-info">Editar <i class='fas fa-edit'></i></a>
                <button type="button" class="btn btn-warning edit-password-btn" data-toggle="modal" data-target="#modal-update-password" data-user-id="{{ $userItem->id }}" data-user-name="{{ $userItem->name }}">Modificar Contraseña</button>
    
                    <form action="{{route('user.destroy',$userItem)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar <i class='fas fa-eraser'></i></button>
                    </form>
                </td>
             </tr>          
                   
                    @endforeach
                </table>
                {{$users->links()}}
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

<!-- Modal de cambio de contraseña  -->
<div class="modal" id="modal-update-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-titulo">Usuario: <strong class="text-danger"><span id="user-name-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.update.password') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" id="user-id">
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--  -->
<script>
    $(document).ready(function() {
        $('.edit-password-btn').click(function() {
            var userId = $(this).data('user-id');
            var userName = $(this).data('user-name');
            $('#modal-update-password').find('#user-id').val(userId);

            $('#modal-update-password').find('#user-name-title').text(userName);

        });  
     });
</script>









@endsection

