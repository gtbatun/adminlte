@csrf
<div class="form-group">
<label for="title">Nombre del area</label>
	<input class="form-control border-0 bg-light shadow-sm"
	type="text"
	name="name"
	value="{{old ('name', $area->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion" name="description">{{old('description',$area->description)}}</textarea>
</div>
<div class="form-group">
	<label for="sucursal">Departamento:</label>
	<select class="form-control"  name="department_id">
		<option value="">Seleccione un departamento</option>
		@foreach($department as $id =>$name)
			<option value="{{ $id }}" @if($id == old('sucursal_id' , $area->department_id)) selected @endif >{{$name}}</option>
		@endforeach
	</select>
</div>


<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('area.index')}}">Cancelar
</a>