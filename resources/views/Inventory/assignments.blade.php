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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>
                        @foreach($user->devices as $device)
                            <div>
                                {{ $device->name }}
                                <form action="{{ route('inventory.destroy', $device->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Desasignar</button>
                                </form>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        <!-- Otras acciones, si las hay -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection