@extends('adminlte::page')
@section('content')
<div class="container-fluid p-2">
    <div class="card">
        <!-- <div class="card-header">
        <h4 class="card-title">Asignaciones de Equipos</h4>
        </div> -->
    <div class="card-body m-1">
        <table id="tb-device" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 20%">Usuario</th>
                    <th>Equipos Asignados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersWithDevices as $userId => $devices)
                <tr>
                    <td>
                        <div class="user-section">
                            <a href="{{route('inventory.create',['user_id' => $userId]) }}"><span>{{ $devices->first()->user_name }}</span></a>                              
                        </div>
                    </td>
                    <td> 
                        <ul class="list-group list-group-horizontal list-group-flush d-flex">
                        @foreach($devices as $device)
                        <a href="#" style="color: black;">
                            <li class="list-group-flush d-flex p-1" >
                                <span title="{{ $device->device_name }}" style="background-color:none;" >{{ $device->device_name }} </span>                                
                            </li>
                        </a>
                        @endforeach 
                        </ul>
                        <!-- <ul class="list-group list-group-horizontal list-group-flush d-flex" >
                        @foreach($devices as $device)
                        <a href="#" style="color: black; border-color: green;">
                            <li class="list-group-item p-1">
                                <span title="{{ $device->device_name }}" style="background-color:none;" >{{ $device->device_name }} </span>                                
                            </li>
                        </a>
                        @endforeach  
                        </ul>  -->
                        
                    </td>
                </tr>                    
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>


@endsection
@section('js')
<script>
    $(document).ready(function() {
        var table = $('#tb-device').DataTable({
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

    });
</script>
@endsection