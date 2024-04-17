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
        
    

    <form class="bg-white py-3 px-4 shadow rounded " id="ticketForm" action="{{route('ticket.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
        <input type="hidden" name="department_id" class="form-control" value="{{auth()->user()->department_id}}" >
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
            <!-- <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    
                    <select name="department_id" class="form-control border-0 bg-light shadow-sm " id="">
                    <option value="">-- Departamento --</option>
                    @foreach($department as  $id => $name)
                    <option value="{{$id}}"
                    @if($id == old('department_id' , $ticket->department_id)) selected @endif >{{$name}}</option>
                    @endforeach                    
                    </select>
                </div>
            </div> -->
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
                <div class="form-control border-0 bg-light shadow-sm">
                    <input type="file" id="fileInput" name="image[]" multiple accept="image/*">                                               
                </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit"  class="btn btn-primary">Crear</button>
            </div>
            <div id="responseMessage"></div>                         
            <div class="row" id="imagePreview"></div>            
            <div id="error-container"></div>
            <script>
    //  
            let files = []; // Array para almacenar los archivos seleccionados

            document.getElementById('fileInput').addEventListener('change', function(event) {
                const preview = document.getElementById('imagePreview');
                // Limpiar la vista previa antes de agregar las nuevas imágenes
                // preview.innerHTML = '';

                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    files.push(file); // Agregar archivo al array de archivos seleccionados
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const thumbnail = document.createElement('div');
                        thumbnail.classList.add('thumbnail');
                        thumbnail.innerHTML = `
                            <img src="${e.target.result}" class="card-img-top" alt="${file.name}">
                            <button type="button" class="btn-remove" data-index="${i}">X</button>
                        `;
                        preview.appendChild(thumbnail);

                        // Agregar un evento click al botón de eliminar
                        thumbnail.querySelector('.btn-remove').addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index')); // Obtener el índice de la imagen                    
                            // Eliminar la imagen del DOM y del array de archivos seleccionados
                            preview.removeChild(thumbnail);
                            files.splice(index, 1);
                            // Actualizar los índices en los botones de eliminar
                            const thumbnails = preview.querySelectorAll('.thumbnail');
                            thumbnails.forEach((thumb, i) => {
                            thumb.querySelector('.btn-remove').setAttribute('data-index', i);
                        });
                        });
                    };

                    reader.readAsDataURL(file);
                }
                // Restablecer el valor del input tipo file
                this.value = '';
            });

    // 

//---------------------- Agregar un evento submit al formulario para enviar los archivos   ----------------------------------
            document.getElementById('ticketForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
                
                // let files = document.getElementById('fileInput').files;
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let title = formData.get('title').trim();
                let description = this.querySelector('textarea[name="description"]').value.trim();
                let area_id = this.querySelector('select[name="area_id"]').value;
                // let department_id = this.querySelector('select[name="department_id"]').value;
                let category_id = this.querySelector('select[name="category_id"]').value;
                // let files = formData.getAll('image[]'); // Obtener todos los archivos del input de tipo file
                // seccion para validar los input y que contegan valores
                // console.log('array de imagenes:',files);
                let errors = {};

                if (!title) {
                    errors.title = ['El título es requerido'];
                }
                if (!description) {
                    errors.description = ['La descripción es requerida'];
                }
                if (!area_id) {
                    errors.area_id = ['El área es requerida'];
                }
                // if (!department_id) {
                //     errors.department_id = ['El departamento es requerido'];
                // }
                if (!category_id) {
                    errors.category_id = ['La categoría es requerida'];
                } 
                          

                for (let i = 0; i < files.length; i++){
                    formData.append('image[]',files[i]);
                }

                formData.append('_token', csrfToken);

                if (Object.keys(errors).length > 0) {
                    mostrarErrores(errors);
                } else {
                    // si todo esta bien, entonces se envian los datos al controlador en la base de datos
                fetch("{{route('ticket.store') }}",{
                    method: 'POST',
                    body:formData
                })
            
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                     // Mostrar mensaje JSON en algún lugar de tu página
                    // por ejemplo, en un div con id "responseMessage"
                    // document.getElementById('responseMessage').innerText = JSON.stringify(data);
                    if (data.redirect_to) {
                        window.location.href = data.redirect_to;
                    }
                })
                .catch(error => {
                    console.error('Esto es lo que manda cuando hay un error:',error);
                    
                    mostrarErrores(error);
                });
            }
            });
//seccion no se esta utilizando por el momneto
            function isImage(file) {
                return file.type.startsWith('image/');
            }

            function mostrarErrores(errors) {
                let errorContainer = document.getElementById('error-container');
                errorContainer.innerHTML = ''; // Limpiar errores anteriores

                for (let campo in errors) {
                    let errorMessages = errors[campo];
                    errorMessages.forEach(message => {
                        let errorElement = document.createElement('div');
                        errorElement.classList.add('alert', 'alert-danger');
                        errorElement.innerText = message;
                        errorContainer.appendChild(errorElement);
                    });
                }
            }
            
         </script>


            
        </div>
    </form>
</div>
@endsection