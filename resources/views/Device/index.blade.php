@extends('adminlte::page')
@section('content')
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
<div>    
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end m-2">
    <a href="{{route('device.create')}}" type="button" class="btn btn-primary p-2 ">Nuevo equipo</a>
    </div>    
    @isset($devices)  
    <div class="container-fluid">
            <div class="col-12 mt-1">
                <div class="card fluid">
                    <div class="card-body ">
                        <div class="table-responsive" >
                        <table id="tb-invent" class="table table-bordered shadow-lg mt-2 table-striped  ">
                            <thead  class="table-dark ">
                                <tr>
                                    <th>ID</th>
                                    <th>TIPO</th>
                                    <th>NOMBRE</th>
                                    <!-- <th>MARCA</th> -->
                                    <th>DEPARTAMENTO</th>
                                    <th>USUARIO</th>
                                    <th>SUCURSAL</th>
                                    <th>ESTATUS</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devices as $deviceItem)                    
                                <tr>
                                    <td>{{$deviceItem->id}}</td>  
                                    <td>{{$deviceItem->tipodevice->name}}</td>
                                    <td>{{$deviceItem->name ?? ''}}</td>
                                    <!-- <td>{{$deviceItem->marca->name ?? ''}}</td> -->
                                    <td>{{$deviceItem->departamento->name ?? ''}}</td>
                                    <td>{{$deviceItem->usuario->name ?? ''}}</td>
                                    <td>{{$deviceItem->sucursal->name ?? ''}}</td>
                                    <td>{{$deviceItem->statusdevice->name ?? ''}}</td> 
                                        
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning asignar-device" data-toggle="modal" data-target="#modal-asignar-device" 
                                        data-device-id="{{ $deviceItem->id }}" data-device-name="{{ $deviceItem->name }}">Asignar</button> 
                                        <a href="{{route('device.edit',$deviceItem)}}" class="btn btn-info">Editar <i class='fas fa-edit'></i></a>

                                        <form action="{{route('device.destroy',$deviceItem)}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Dell <i class='fas fa-eraser'></i></button>
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
<div class="modal" id="modal-asignar-device">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-titulo">Equipo: <strong class="text-danger"><span id="device-title"></span></strong></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.update.password') }}" method="post">
                    @csrf
                    <input type="hidden" name="device_id" id="device-id">
                    <div class="form-group">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Seleccione un usuario</option>
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="coment">Comentario</label>
                        <input type="text" name="coment" id="coment" class="form-control">
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
        $('.asignar-device').click(function() {
            var deviceId = $(this).data('device-id');
            var deviceName = $(this).data('device-name');
            $('#modal-asignar-device').find('#device-id').val(deviceId);
            $('#modal-asignar-device').find('#device-title').text(deviceName);

        });  
     });
</script>
<script>
    
$(document).ready(function() {
    $('#tb-invent').DataTable({
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
            "autoWidth": true,
            // "columnDefs": [
            //     { "width": "5%", "targets": 0 },
            //     { "width": "10%", "targets": 1 },
            //     { "width": "10%", "targets": 2 },
            //     { "width": "5%", "targets": 3 },
            //     { "width": "10%", "targets": 4}]
    } );
} );
</script>
@endsection










