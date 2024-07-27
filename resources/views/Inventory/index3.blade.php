@extends('adminlte::page')
@section('content')

<div class="container-fluid">
    <h2 class="text-center display-4">Buscar</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
        <form action="">
            <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Buscar usuario">
                <div class="input-group-append">
                <button type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
                </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- ------------------------------------------------------------------------------------------ -->
<div class="container">
    <div class="card">
        <h1>Asignaciones de Equipos</h1>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Asignar Equipos a Usuario</a>
    </div>
    <div class="card-body p-0">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Equipos Asignados</th>
                <!-- <th>Acciones</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <div class="form-group">
                        <label for="">{{ $user->name }}</label>
                        </div>  
                        <div class="form-group">
                        <span>{{$user->email}}</span>
                        </div>     
                    </td>
                    <td>                        
                    <table>
                        @foreach($user->devices as $device)
                                <tr>
                                    <td>{{ $device->name }}</td>
                                    <td>
                                    <form action="{{ route('inventory.destroy', $device->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Desasignar</button>
                                    </form>
                                    </td>
                                </tr>
                        @endforeach
                        
                        </table>
                    </td>
                    <!-- <td> -->
                        <!-- Otras acciones, si las hay -->
                    <!-- </td> -->
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection