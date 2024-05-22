@csrf
<div class="form-group">
<label for="title">Nombre de la sucursal</label>
	<input class="form-control border-0 bg-light shadow-sm"
	type="text"
	name="name"
	value="{{old ('name', $sucursal->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion"name="description">{{ old('description',$sucursal->description)}}</textarea>
	
</div>

<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('sucursal.index')}}">Cancelar
</a>