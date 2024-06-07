<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->

@extends('adminlte::page')
@section('content')

@can('update', $ticket)
<div class="container">
    <div class="col-12">
        <div>
            <h2>Editar Ticket</h2>
        </div>
        <div>
            <a href="{{route('ticket.index')}}" class="btn btn-primary">Volver</a>
        </div>
    </div>
    
    @include('partials.validation-errors')

    <form action="{{route('ticket.update',$ticket)}}" method="POST" enctype="multipart/form-data" >
        @csrf
        @method('PUT')
        <!-- <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" > -->
        <input type="hidden" name="user_id" class="form-control" value="{{$ticket->user_id}}" >
        <input type="hidden" name="status_id" class="form-control" value="1" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Ticket:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Titulo" value="{{old ('title',$ticket->title)}}" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Descripción:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Descripción...">{{old ('description', $ticket->description)}}</textarea>
                </div>
            </div>
           
            <!-- ------------------------------------------------------------------------------------------ -->
            <div class="col-xs-12 col-sm-4 col-md-4 mt-2">
                <label for="country">Departamento</label>
                <select name="department_id" id="departamento" class="form-control">
                    @foreach($department as $departmentItem)
                        <option value="{{ $departmentItem->id }}" {{ $ticket->department_id == $departmentItem->id ? 'selected' : '' }}>
                            {{ $departmentItem->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 mt-2">
                <label for="state">Areas</label>
                <select name="area_id" id="area" class="form-control">
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ $ticket->area_id == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 mt-2">
                <label for="city">Categoria</label>
                <select name="category_id" id="categoria" class="form-control">
                    @foreach($categorias as $category)
                        <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- ------------------------------------------------------------------------------------------ -->
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                    <strong>Adjuntos</strong>
                   
                    @if(!empty($ticket->image))
                        @foreach(explode(',', $ticket->image) as $imageItem )
                        <div class="row">
                        <img src="{{asset('storage/images/'. $imageItem)}}" alt="{{ $ticket->id }}" class="img-thumbnail">
                        <!-- <a href="#" class="btn btn-sm btn-danger delete-image" data-image="{{ $imageItem }}">X</a> -->                        
                        </div>
                        @endforeach
                    @endif
                    <!-- -------------------------------------------- -->
                    <!-- <input type="file" class="form-control border-0 bg-light shadow-sm" name="image[]" multiple id="fileInput"> -->
                    <input type="file" class="form-control border-0 bg-light shadow-sm" name="image[]" multiple id="fileInput">
                    <ul id="preview"></ul>
                    <script>
                    document.getElementById('fileInput').addEventListener('change', function(event) {
                        const files = event.target.files;
                        const preview = document.getElementById('preview');

                        preview.innerHTML = '';

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            const listItem = document.createElement('li');

                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.width = 200;
                            img.height = 200;

                            const removeButton = document.createElement('button');
                            removeButton.textContent = 'X';
                            removeButton.addEventListener('click', () => {
                                event.target.value = null;
                                preview.removeChild(listItem);
                            });

                            listItem.appendChild(img);
                            listItem.appendChild(removeButton);
                            preview.appendChild(listItem);
                        }
                    });
                     
                </script>

                    <!-- -------------------------------------------  -->
                </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.getElementById('departamento').addEventListener('change', function() {
        var departamentoId = this.value;
        fetch(`/get-area/${departamentoId}`)
            .then(response => response.json())
            .then(data => {
                var areaSelect = document.getElementById('area');
                areaSelect.innerHTML = '<option value="">Selecciona un área</option>';
                data.forEach(area => {
                    areaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                });

                // Clear categoria select when departamento changes
                var categoriaSelect = document.getElementById('categoria');
                categoriaSelect.innerHTML = `<option value="">Selecciona una categoría</option>`;
            });
    });
    document.getElementById('area').addEventListener('change', function() {
        var areaId = this.value;
        fetch(`/get-category/${areaId}`)
            .then(response => response.json())
            .then(data => {
                var categoriaSelect = document.getElementById('categoria');
                categoriaSelect.innerHTML = '<option value="">Selecciona una categoría</option>';
                data.forEach(categoria => {
                    categoriaSelect.innerHTML += `<option value="${categoria.id}">${categoria.name}</option>`;
                });
            });
    });
</script>

@endsection
@endcan