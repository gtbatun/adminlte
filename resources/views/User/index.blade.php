@extends('adminlte::page')
@section('content')
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/core/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('assets/js/datatables.min.js')}}"></script>
<!-- <script src="{{asset('assets/css/datatables.min.css')}}"></script> -->

<link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">


<!-- <link href="https://cdn.datatables.net/v/bs4/dt-2.1.6/r-3.0.3/datatables.min.css" rel="stylesheet"> -->
 
<!-- <script src="https://cdn.datatables.net/v/bs4/dt-2.1.6/r-3.0.3/datatables.min.js"></script>  -->

<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.1.6/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.1.6/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.js"></script>


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

    <!-- -------------------------- -->
  
    <!-- -------------------------------------------------------------------------------------------------------------- -->

    <div class="container-fluid">
            <div class="col-12 mt-0">
                <div class="card fluid">
                    <div class="card-body ">
                        <div class="table-responsive">
                        <table id="tb-users" class="table table-striped responsive nowrap" style="width:100%">
                            <thead  class="table-dark ">
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>EMAIL</th>
                                    <th>SUCURSAL</th>
                                    <th>VERIFICADO</th>
                                    <th>DEPARTAMENTO</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $userItem)
                                <tr>
                                    <td>{{$userItem->id}}</td>  
                                    <td>{{$userItem->name}}</td>
                                    <td>{{$userItem->email}}</td>
                                    <td>
                                    @if (is_null($userItem->sucursal_id))  Sin sucursal @else {{$userItem->sucursal->name}} @endif
                                    </td>
                                    <td>
                                        @if (is_null($userItem->email_verified_at))
                                            <a href="{{ route('admin.verify-email', $userItem->id) }}" class="btn btn-primary">Verificar</a>
                                        @else  Verificado @endif
                                    </td>
                                    <td>
                                        @if(isset($userItem->department->name))
                                            {{$userItem->department->name }}                   
                                        @else
                                            Sin Departamento
                                        @endif 
                                    </td>
                                    <td >
                                    <div class="btn-group justify-content-center">
                                        <a href="{{route('user.edit',$userItem)}}" class="btn btn-info m-1"><i class='fas fa-edit'></i></a>
                                        <button type="button" class="btn btn-warning edit-password-btn m-1" data-toggle="modal" data-target="#modal-update-password" data-user-id="{{ $userItem->id }}" data-user-name="{{ $userItem->name }}"><i class='fas fa-key'></i></button> 
                                        <form action="{{route('user.destroy',$userItem)}}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger m-1"><i class='fas fa-times'></i></button>
                                            </form>
                                        <!-- <button type="submit" class="btn btn-danger d-block d-sm-none m-1 clickable-row"><i class='fas fa-plus'></i></button>    -->
                                    </div>                                    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
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
<script>    
$(document).ready(function() {

    // new DataTable('#tb-users', {
    //         responsive: true
    //     });
    
    new DataTable('#tb-users',{
        layout: {
                    topStart: {
                        buttons: ['colvis']
                    }
                },
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
           
    });

    $('.edit-password-btn').click(function() {
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        $('#modal-update-password').find('#user-id').val(userId);

        $('#modal-update-password').find('#user-name-title').text(userName);

    });
    // -----------------------------
    function handleRowClick() {
            if (window.innerWidth < 768) {
                $('.clickable-row').off('click'); // Deshabilitar clic en pantallas pequeñas
                $('.show-details-btn').on('click', function() {
                    var target = $(this).data('bs-target');
                    $(target).collapse('toggle');
                });
            } else {
                $('.show-details-btn').off('click'); // Deshabilitar clic en pantallas grandes
                $('.collapse').collapse('hide');
            }
        }

        handleRowClick();
        $(window).on('resize', handleRowClick);

} );
</script>
@endsection
