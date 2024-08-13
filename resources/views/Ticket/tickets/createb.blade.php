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
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                    <strong>Adjuntos</strong>
                    <!-- <input type="file" name="image[]" class="form-control border-0 bg-light shadow-sm" id="seleccionArchivos" multiple accept="image/*" >
                    <img id="preview" class="container">  -->
                    <input type="file" class="form-control border-0 bg-light shadow-sm" name="image[]" multiple id="fileInput" value="">
                    <input type="text" id="filesInput" name="filesInput" value="">
                    <div id="preview"></div>
                    <!-- ------------------- -->
                    <script>
                        // let fileNames = '';
                        let files = [];
                        let filesNames = [];
                        document.getElementById('fileInput').addEventListener('change', function(event) {
                            files = event.target.files;
                            const preview = document.getElementById('preview');
                            // console.log(preview);

                            for (let i = 0; i < files.length; i++) {                                
                                const file = files[i];
                                filesNames.push(files[i]);
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    const thumbnail = document.createElement('div');
                                    thumbnail.classList.add('thumbnail');
                                    thumbnail.innerHTML = `
                                    <img src="${e.target.result}" alt="${file.name}">
                                    <button type="button" onclick="removeImage(this, ${i})">X</button>
                                    `;
                                    preview.appendChild(thumbnail);
                                // fileNames += files[i].name;
                                // if (i < files.length - 1) {    
                                // fileNames += ',';
                                // }
                                // let fileNames = '';
                                // for (let i = 0; i < files.length; i++) {
                                //     fileNames += files[i].name;
                                //     if (i < files.length - 1) {
                                //         fileNames += ', '; // Agregar una coma y un espacio entre cada nombre de archivo
                                //     }
                                // }
                                    


                            
                            document.getElementById('filesInput').value = files[i].name;
                                };
                                // document.getElementById('filesInput').value = fileNames;

                                reader.readAsDataURL(file);
                                // console.log(file);
                                // $fileNames += file[i].name;                                                                
                            
                            }
                            console.log("Datos de files",files);
                            console.log("Datos de filesNames",filesNames);

                            // document.getElementById('filesInput').value = files.name;
                            // console.log(files);
                        });

                        function removeImage(button,index) {
                            // const thumbnail = button.parentElement;
                            // thumbnail.remove();
                            // const fileInput = document.getElementById('fileInput');
                            // fileInput.value = null;
                            // 
                            const thumbnail = document.getElementById('preview');
                            thumbnail.removeChild(thumbnail.childNodes[index]);
                            files = Array.from(files).filter((_, i) => i !== index);
                            console.log('Despues del remove',files);
                            console.log("Datos de filesNames despues del remove",filesNames);


                        }
                    </script>

                    <!-- ------------------- -->                                   
                        
                </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" class="btn btn-primary">   Crear   </button>
            </div>
        </div>
    </form>
</div>
@endsection