@extends('adminlte::page')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Asignaciones de equipos</h3>
        <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button> -->
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
            <tr>
                <th>Project Name</th>
                <th>Team Members</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>fdfd</td>
                    <td>
                        <ul>
                        <li class="list-inline-item">
                        <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
                        </li>
                        <li class="list-inline-item">
                        <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar2.png">
                        </li>
                        <li class="list-inline-item">
                        <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar3.png">
                        </li>
                        <li class="list-inline-item">
                        <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar4.png">
                        </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <h4>Asignaciones de Equipos</h4>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
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
                        <ul class="list-group list-group-horizontal d-flex" >
                        @foreach($devices as $device)
                        <a href="#" style="color: black;">
                            <li class="list-group-item ">
                                <span title="{{ $device->device_name }}">{{ $device->device_name }} </span>                                
                            </li>
                            </a>
                        @endforeach  
                        </ul>                                             
                    </td>
                </tr>                    
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>


@endsection