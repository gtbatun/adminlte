@csrf
<div class="form-group">
<label for="title">Nombre del Departamento</label>
	<input class="form-control border-0 bg-light shadow-sm"	type="text"	name="name"	value="{{old ('name', $department->name)}}" >
</div>

<div class="form-group">
<label for="description">Descripcion</label>
	{{-- <input type="text" name="description"> --}}
	<textarea class="form-control border-0 bg-light shadow-sm" placeholder="Descripcion" name="description">{{ old('description',$department->description)}}</textarea>
	<!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
</div>
           
<div class="form-group">
	<label for="sucursal">Sucursal:</label>
	<select name="sucursal_ids[]" class="form-control" multiple required>
		<option value="">Seleccione una sucursal</option>
		@foreach($sucursal as $id =>$name)
			<!-- <option value="{{ $id }}" @if($id == old('sucursal_ids' , $department->sucursal_ids)) selected @endif >{{$name}}</option> -->
			<option value="{{ $id }}" @if(in_array($id, old('sucursal_ids', json_decode($department->sucursal_ids, true) ?? []))) selected @endif>{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="enableforticket">Aceptar tickets:</label>
	<select name="enableforticket" class="form-control" required>
	<option value="0" {{ old('enableforticket', $department->enableforticket) == 0 ? 'selected' : '' }}>No</option>
	<option value="1" {{ old('enableforticket', $department->enableforticket) == 1 ? 'selected' : '' }}>Sí</option>	
	</select>
</div>

<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('department.index')}}">Cancelar
</a>