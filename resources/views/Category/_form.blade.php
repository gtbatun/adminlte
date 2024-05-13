@csrf
<div class="form-group">
<label for="title">Nombre del Categoria</label>
	<input class="form-control border-0 bg-light shadow-sm"
	type="text"
	name="name"
	value="{{old ('name', $category->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion" name="description">{{ old('description',$category->description)}}</textarea>
	<!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
</div>

<div class="form-group">
	<strong>Asignar a:</strong>
	<select name="area_id" id="area" class="form-control border-0 bg-light shadow-sm " required>
	<option value="">Seleccionar un Area</option>
	@foreach($areas as  $id => $name)
	<option value="{{$id}}" @if($id == old('department_id' , $category->area_id)) selected @endif >{{$name}}</option>
	@endforeach
	</select>
</div>


<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('category.index')}}">Cancelar
</a>