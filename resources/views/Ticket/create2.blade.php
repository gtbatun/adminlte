<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
<style>
        #preview {
            display: flex;
            flex-wrap: wrap;
        }

        .thumbnail {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .thumbnail img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .thumbnail button {
            margin-top: 5px;
            border: none;
            background-color: red;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>

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
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
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
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
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
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
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
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <input type="text" class="form-control" id="filesNames">
            </div>
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <strong>Adjuntos</strong>
                <div class="form-control border-0 bg-light shadow-sm">
                <input type="text" id="imgs" name="image[]"  >
                <input type="hidden" id="files" name="files[]"  >
                <input type="file" id="fileInput" name="imgs[]" multiple>
                <!-- <input type="text" > -->
                                                
            </div>
            <div id="preview">

            </div>
            <script>

                
                let files = [];
                let filesNames = [];
                

                document.getElementById('fileInput').addEventListener('change', function(event){
                    // const files = event.target.files; 
                  const filesList = event.target.files; //recien agregado
                  const preview = document.getElementById('preview');
                
                  for (let i = 0; i < filesList.length; i++){
                    const file = filesList[i];
                    files.push(file);
                    filesNames.push(file.name);

                    const reader = new FileReader();
                    reader.onload = function(e){
                        const thumbnail = document.createElement('div');
                        const index = files.length - 1; // Obtener el índice actual en el array files
                        thumbnail.dataset.index = index; // Almacenar el índice en un atributo de datos del elemento            
                        thumbnail.classList.add('thumbnail');
                        thumbnail.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <button type="button" onclick="removeImage(this,${i})">X</button>
                        <p>${file.name}</p>
                        `;
                        preview.appendChild(thumbnail);     
                        console.log('Segun el push de filesnames',filesNames);
                    console.log('Segun el push de files',files);
                    // seccion para recorrer el file
                    
                    // 
                    };
                    reader.readAsDataURL(file);
                    // reader.readAsDataURL(file,filesNames);


                  }

                //   console.log("datos de las img",files);
                //   console.log("nombres de las img",filesNames);

                //   document.getElementById('filesNames').value = filesNames;
                updateFilesNamesInput();
                

                });

                
                    function removeImage(button){
                        // function removeImage(button,index){
                    // const preview = document.getElementById('preview');
                    const thumbnail = button.parentElement;
                    const index = thumbnail.dataset.index; // Obtener el índice almacenado en el atributo de datos


                    // if(index !== -1 && thumbnail.parentNode === preview){
                        if (index !== undefined) {
                        // preview.removeChild(thumbnail);
                        thumbnail.remove();
                        files.splice(index, 1);
                        filesNames.splice(index, 1);
                        updateFilesNamesInput();

                        console.log('Despues del remove',files);
                        console.log('nombres Despues del remove',filesNames);
                         // Actualizar los índices almacenados en los elementos restantes
                        const thumbnails = document.querySelectorAll('.thumbnail');
                        thumbnails.forEach((thumb, i) => {
                            thumb.dataset.index = i;
                        });
                    }


                    // console.log('Despues del remove',files);
                    // console.log('nombres Despues del remove',filesNames);
                    // document.getElementById('filesNames').value = filesNames;
                    // document.getElementById('fileInput').value = files;
                    
                }
                function updateFilesNamesInput() {
                    document.getElementById('filesNames').value = filesNames.join(',');
                    document.getElementById('files').value = files;
                    document.getElementById('imgs').value = JSON.stringify(files);
                    // files1 = Array.from(files);
                    // document.getElementById('imgs').value = files1;
                    
                    // console.log('files1:',files1);
                }
            </script>


            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" onclick='updateFilesNamesInput();' class="btn btn-primary">Crear</button>
            </div>
        </div>
    </form>
</div>
@endsection