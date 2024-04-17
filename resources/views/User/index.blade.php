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
                <td>{{$userItem->department->name}}</td>               
                <td>{{$userItem->created_at->diffForHumans(null, false, false, 1)}}</td>             
                <td>
                <a href="{{route('user.edit',$userItem)}}" class="btn btn-info">Editar <i class='fas fa-edit'></i></a>
                <!-- <a href="" class="btn btn-warning" data-target="#modal-create-category">Contraseña <i class='fas fa-edit'></i></a> -->
                <!-- <a type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-update-password" data-user-id="{{$userItem->id}}" href="">Contraseña</a> -->
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-update-password" data-user-id="{{ $userItem->id }}">Contraseña</button>
                <!-- <button type="button" class="btn btn-warning" onclick="mostrarId({{ $userItem->id }})">Modificar Contraseña</button> -->
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
 <!-- <h1>
    Tickets
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-category">
        Crear
    </button>
    </h1> -->
<!-- modal -->
<div class="modal fade" id="modal-create-category">
    <div class="modal-dialog">
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar contraseña</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
            <div class="modal-body">
                <!-- <p>Proximamente, Formulario....</p> -->
                <p>Usuario ID: <span id="user-id"></span></p>
                <div class="row mb-3">
                <label for="password" class="col-md-4 col-lg-3 col-form-label ">Nueva Contraseña</label>
                <div class="col-md-8 col-lg-9">
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" >
                </div>
            </div>
            <div class="row mb-3">
                <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label ">Repetir Contraseña</label>
                <div class="col-md-8 col-lg-9">
                <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror">
                </div>
            </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal" id="modal-update-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modificar contraseña</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <input type="text" name="user_id" id="user-id">
                    <div class="form-group">
                        <label for="new-password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="new-password" name="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#modal-update-password').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var userId = button.data('user-id'); // Extraer el ID de usuario de los datos del botón

        // Actualizar el campo oculto con el ID de usuario
        var modal = $(this);
        modal.find('.modal-body #user-id').val(userId);
    });
</script>

<script>
    function mostrarId(userId) {
        console.log('ID de usuario:', userId);
    }
</script>

@endsection

