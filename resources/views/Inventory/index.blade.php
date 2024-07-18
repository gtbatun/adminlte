@extends('adminlte::page')
@section('content')
<div class="container">
    <h1>Asignaciones de Equipos</h1>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Asignar Equipos a Usuario</a>
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
@endsection