@csrf
<div class="form-group">
<label for="title">Titulo</label>
	<input class="form-control border-0 bg-light shadow-sm"
	type="text"
	name="name"
	value="{{old ('name', $area->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm"
	name="description">
	{{ old('description',$area->description)}}
</textarea>
</div>

<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('area.index')}}">Cancelar
</a>