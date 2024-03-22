@csrf
<div class="form-group">
<label for="title">Nombre del area</label>
	<input class="form-control border-0 bg-light shadow-sm"
	type="text"
	name="name"
	value="{{old ('name', $status->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm"
	name="description">
	{{ old('description',$status->description)}}
	</textarea>
	<!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
</div>

<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('status.index')}}">Cancelar
</a>