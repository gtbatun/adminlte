<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
    <!-- {{$ticket}} -->
    
    @include('partials.validation-errors')

    <form action="{{route('ticket.update',$ticket)}}" method="POST" enctype="multipart/form-data" >
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
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
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="area_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Elija un Area --</option>
                    @foreach($areas as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('area_id' , $ticket->area_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="department_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Departamento --</option>
                    @foreach($department as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $ticket->department_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <!--  -->
                    <select name="category_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Categoria --</option>
                    @foreach($category as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $ticket->category_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div>
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2">
                    <strong>Adjuntos</strong>
                   
                    @if(!empty($ticket->image))
                        @foreach(explode(',', $ticket->image) as $imageItem )
                        <img src="{{asset('storage/images/'. $imageItem)}}" alt="{{ $ticket->id }}" class="img-thumbnail">
                        <a href="#" class="btn btn-sm btn-danger delete-image" data-image="{{ $imageItem }}">X</a>
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
                            removeButton.textContent = 'Remove';
                            removeButton.addEventListener('click', () => {
                                event.target.value = null;
                                preview.removeChild(listItem);
                            });

                            listItem.appendChild(img);
                            listItem.appendChild(removeButton);
                            preview.appendChild(listItem);
                        }
                    });
                     

                        // Script para eliminar la imagen
                        $('.delete-image').click(function(e) {
                            e.preventDefault();
                            var imageUrlToDelete = $(this).data('image');

                            // Confirmar antes de eliminar
                            if (confirm("¿Estás seguro de que quieres eliminar esta imagen?")) {
                                // Realizar una solicitud AJAX para eliminar la imagen
                                $.ajax({
                                    url: '/eliminar-imagen', // Ruta de la solicitud
                                    method: 'POST', // Método de la solicitud
                                    data: {
                                        _token: '{{ csrf_token() }}', // Token CSRF para protección
                                        image_url: imageUrlToDelete // URL de la imagen a eliminar
                                    },
                                    success: function(response) {
                                        // Actualizar la vista después de eliminar la imagen
                                        // Por ejemplo, puedes recargar la página o actualizar la lista de imágenes sin recargar la página
                                    },
                                    error: function(xhr, status, error) {
                                        // Manejar errores si la solicitud falla
                                        console.error(error);
                                    }
                                });
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
@endcan
@endsection

