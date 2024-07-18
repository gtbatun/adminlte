@csrf
<div class="form-group">
	<label for="tipo_equipo_id">Tipo de equipo</label>
	<select name="tipo_equipo_id" class="form-control" >
        <option value="">Seleccionar una clasificacion</option>
		@foreach($tipo_equipo as  $id => $name )
        <option value="{{$id}}" @if($id == old('tipo_equipo_id', $equipo->tipo_equipo_id)) selected @endif>{{$name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
<label for="title">Hostname</label>
	<input class="form-control border-0 bg-light shadow-sm"	type="text"	name="name"	value="{{old ('name', $equipo->name)}}" autocomplete="off">
</div>
<div class="form-group">
<label for="title">Marca</label>
	<select name="marca_id" class="form-control" >
        <option value="">Seleccionar un departamento</option>
		@foreach($marca as  $id => $name )
        <option value="{{$id}}" @if($id == old('marca_id', $equipo->marca_id)) selected @endif>{{$name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
<label for="title">Serie</label>
	<input class="form-control border-0 bg-light shadow-sm"	type="text"	name="serie" value="{{old ('serie', $equipo->serie)}}" >
</div>

<div class="form-group">
<label for="title">Almacenamiento</label>
@foreach($almacenamiento as  $id => $name )
	<div class="form-check">
	<input class="form-check-input" type="radio" name="almacenamiento_id" value="{{ $id }}" id="equipo_{{ $id }}" 
	@if($id == old('almacenamiento_id', $equipo->almacenamiento_id)) checked @endif>
		<label class="form-check-label" for="almacenamiento_{{ $id }}">{{ $name }}</label>
	</div>
	@endforeach
</div>

<div class="form-group">
<label for="title">Procesador</label>
	@foreach($procesador as  $id => $name )
	<div class="form-check">
	<input class="form-check-input" type="radio" name="procesador_id" value="{{ $id }}" id="equipo_{{ $id }}" 
	@if($id == old('procesador_id', $equipo->procesador_id)) checked @endif>
		<label class="form-check-label" for="procesador_{{ $id }}">{{ $name }}</label>
	</div>
	@endforeach
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion" name="description">{{ old('description',$equipo->description)}}</textarea>
	
</div>

<div class="form-group">
	<label for="sucursal_id">Sucursal</label>
	@foreach($sucursal as  $id => $name )
	<div class="form-check">
	<input class="form-check-input" type="radio" name="sucursal_id" value="{{ $id }}" id="equipo_{{ $id }}" 
	@if($id == old('sucursal_id', $equipo->sucursal_id)) checked @endif>
		<label class="form-check-label" for="sucursal_{{ $id }}">{{ $name }}</label>
	</div>
	@endforeach
</div>

<!-- <div class="form-group">
	<label for="department">Departamento</label>
	<select name="department_id" class="form-control" >
        <option value="">Seleccionar un departamento</option>
		@foreach($department as  $id => $name )
        <option value="{{$id}}" @if($id == old('department_id', $equipo->department_id)) selected @endif>{{$name}}</option>
		@endforeach
	</select>
</div> -->

<div class="form-group">
	<label for="sucursal">Estatus</label>
	<select name="statusdevice_id" class="form-control" >
		<option value="">Seleccionar un estatus</option>
		@foreach($status as  $id => $name )
        <option value="{{$id}}" @if($id == old('statusdevice_id', $equipo->statusdevice_id)) selected @endif>{{$name}}</option>
		@endforeach		
	</select>
</div>



<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('inventory.index')}}">Cancelar
</a>