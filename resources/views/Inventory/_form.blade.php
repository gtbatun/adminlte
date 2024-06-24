@csrf
<div class="form-group">
<label for="title">Nombre del Equipo</label>
	<input class="form-control border-0 bg-light shadow-sm"	type="text"	name="name"	value="{{old ('name', $equipo->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion" name="description">{{ old('description',$equipo->description)}}</textarea>
	
</div>

<div class="form-group">
	<label for="sucursal">Sucursal</label>
	<select name="sucursal_id" class="form-control" >
        <option value="">Seleccionar una sucursal</option>
		@foreach($sucursal as  $id => $name )
        <option value="{{$id}}" @if($id == old('sucursal_id', $equipo->sucursal_id)) selected @endif>{{$name}}</option>
		@endforeach
	</select>
</div>



<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('inventory.index')}}">Cancelar
</a>