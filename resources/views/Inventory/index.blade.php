@extends('adminlte::page')
@section('content')
<!-- {{$devices}}
{{$users}} -->
<div class="container">
    <h1>Asignar Equipos a Usuario</h1>
    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Usuario</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="">Seleccione un usuario</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="devices">Equipos</label>
            @foreach($devices as $device)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="device_ids[]" value="{{ $device->id }}" id="device_{{ $device->id }}">
                    <label class="form-check-label" for="device_{{ $device->id }}">{{ $device->name }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Asignar</button>
    </form>
</div>
@endsection