<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="col-12">
        <div>
            <h2>Crear Ticket</h2>
        </div>
        <div>
            <a href="{{route('ticket.index')}}" class="btn btn-primary">Volver</a>
        </div>
    </div>
    

    @include('partials.validation-errors') 

    <form action="{{route('ticket.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
        <input type="hidden" name="status_id" class="form-control" value="1" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Ticket:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Titulo" value="{{old ('title')}}" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Descripción:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Descripción...">{{old ('description')}}</textarea>
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
                    <input type="file" name="image" class="form-control border-0 bg-light shadow-sm" id="seleccionArchivos" accept="image/*" >
                    <img id="imagenPrevisualizacion" class="container">                    
                    <script>
                        // Obtener referencia al input y a la imagen
                            const $seleccionArchivos = document.querySelector("#seleccionArchivos"),
                            $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion");

                            // Escuchar cuando cambie
                            $seleccionArchivos.addEventListener("change", () => {
                            // Los archivos seleccionados, pueden ser muchos o uno
                            const archivos = $seleccionArchivos.files;
                            // Si no hay archivos salimos de la función y quitamos la imagen
                            if (!archivos || !archivos.length) {
                                $imagenPrevisualizacion.src = "";
                                return;
                            }
                            // Ahora tomamos el primer archivo, el cual vamos a previsualizar
                            const primerArchivo = archivos[0];
                            // Lo convertimos a un objeto de tipo objectURL
                            const objectURL = URL.createObjectURL(primerArchivo);
                            // Y a la fuente de la imagen le ponemos el objectURL
                            $imagenPrevisualizacion.src = objectURL;
                            });
                    </script>
                </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </div>
    </form>
</div>
@endsection