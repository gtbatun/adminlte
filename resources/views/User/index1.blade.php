@extends('adminlte::page')
@section('content')
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/core/bootstrap.bundle.min.js')}}"></script>



<div>    
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
            <div class="col-12 mt-1">
                <div class="card fluid">
                    <div class="card-body ">
                        <div class="table-responsive" >
                        <table id="tb-users" class="table table-bordered dt-responsive mt-2 table-striped  ">
                            <thead  class="table-dark ">
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th class="d-none d-md-table-cell">EMAIL</th>
                                    <th class="d-none d-md-table-cell">SUCURSAL</th>
                                    <th class="d-none d-md-table-cell">VERIFICADO</th>
                                    <th class="d-none d-md-table-cell">DEPARTAMENTO</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $userItem)                    
                                <!-- <tr data-bs-toggle="collapse" data-bs-target="#details{{ $userItem->id }}" aria-expanded="false" aria-controls="details{{ $userItem->id }}"> -->
                                <tr class="clickable-row" data-bs-toggle="collapse" data-bs-target="#details{{ $userItem->id }}" aria-expanded="false" aria-controls="details{{ $userItem->id }}">
                                    <td>{{$userItem->id}}</td>  
                                    <td>{{$userItem->name}}</td>
                                    <td class="d-none d-md-table-cell">{{$userItem->email}}</td>
                                    <td class="d-none d-md-table-cell">
                                    @if (is_null($userItem->sucursal_id))                                        
                                            Sin sucursal
                                        @else
                                            {{$userItem->sucursal->name}}
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if (is_null($userItem->email_verified_at))
                                            <a href="{{ route('admin.verify-email', $userItem->id) }}" class="btn btn-primary">Verificar</a>
                                        @else
                                            Verificado
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if(isset($userItem->department->name))
                                            {{$userItem->department->name }}                   
                                        @else
                                            Sin Departamento
                                        @endif 
                                    </td>
                                    <td>
                                    <a href="{{route('user.edit',$userItem)}}" class="btn btn-info">Ed <i class='fas fa-edit'></i></a>
                                    <button type="button" class="btn btn-warning edit-password-btn " data-toggle="modal" data-target="#modal-update-password" data-user-id="{{ $userItem->id }}" data-user-name="{{ $userItem->name }}">Pass</button>            
                                        <form action="{{route('user.destroy',$userItem)}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">Dell <i class='fas fa-eraser'></i></button>
                                        </form>
                                       
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
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
@endsection
@section('js')
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
<script>
    
$(document).ready(function() {
    $('#tb-users').DataTable({
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
            },
            responsive: true,
            autoWidth: true,
            // "columnDefs": [
            //     { "width": "20%", "targets": 1 },
            //     { "width": "20%", "targets": 2 },
            //     { "width": "5%", "targets": 3 }]
    } );
} );
</script>
@endsection
