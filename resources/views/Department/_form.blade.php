@csrf
<script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
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
	<label for="enableforticket">Aceptar tickets:</label>
	<select name="enableforticket" id="enableforticket" class="form-control" required>
	<option value="0" {{ old('enableforticket', $department->enableforticket) == 0 ? 'selected' : '' }}>No</option>
	<option value="1" {{ old('enableforticket', $department->enableforticket) == 1 ? 'selected' : '' }}>Sí</option>	
	</select>
</div>
           
<div class="form-group" id="suc_for_ticket_group" style="display: none;">
	<label for="sucursal">Sucursal donde acepta ticket:</label>
	<select name="suc_for_ticket[]" class="form-control" multiple>
		<!-- <option value="">Seleccione una sucursal</option> -->
		@foreach($sucursal as $id => $name)
			<!-- <option value="{{ $id }}" @if($id == old('sucursal_ids' , $department->sucursal_ids)) selected @endif >{{$name}}</option> -->
			<option value="{{ $id }}" @if(in_array($id, old('sucursal_ids', json_decode($department->suc_for_ticket, true) ?? []))) selected @endif>{{ $name }}</option>
		@endforeach
	</select>
</div> 

<div class="form-group">
	<label for="sucursal">Sucursal Dep:</label>
	<select name="sucursal_ids[]" class="form-control" multiple required>
		@foreach($sucursal as $id => $name)
			<option value="{{ $id }}" @if(in_array($id, old('sucursal_ids', json_decode($department->sucursal_ids, true) ?? []))) selected @endif>{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-check">
	<input type="hidden" name="multi" value="0">
	<input class="form-check-input" type="checkbox" value="1" name="multi" {{ $department->multi == 1 ? 'checked' : '' }}>
	<label class="form-check-label text-success" for="status_id"><strong>Multi sucursal</strong></label>            
</div>


<button class="btn btn-primary btn-lg btn-block">{{$btnText}}</button>
<a class="btn btn-link btn-block"
href="{{route('department.index')}}">Cancelar
</a>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var enableForTicketSelect = document.getElementById('enableforticket');
        var sucForTicketGroup = document.getElementById('suc_for_ticket_group');

        // Show or hide the 'suc_for_ticket' group based on the initial value
        toggleSucForTicketGroup();

        // Add event listener to the select element
        enableForTicketSelect.addEventListener('change', toggleSucForTicketGroup);

        function toggleSucForTicketGroup() {
            if (enableForTicketSelect.value == '1') {
                sucForTicketGroup.style.display = 'block';
            } else {
                sucForTicketGroup.style.display = 'none';
            }
        }
    });
</script>