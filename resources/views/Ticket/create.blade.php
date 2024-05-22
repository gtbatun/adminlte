<!-- optimizar las consultas de las select options, se esta realizando 3 consultas una por cada opcion -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
           
            
            <!--  -->
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <select name="area_id" id="area" class="form-control border-0 bg-light shadow-sm " required>
                    <option value="">Seleccionar un Area</option>
                    @foreach($areas as  $id => $name)
                    <option value="{{$id}}" @if($id == old('area_id' , $ticket->area_id)) selected @endif >{{$name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                <div class="form-group">
                    <strong>Asignar a:</strong>
                    <select name="category_id" id="category" class="form-control border-0 bg-light shadow-sm " required>
                    <option value="">Seleccionar un categoria</option>                    
                    </select>
                </div>
            </div>
            <!--  -->
            <!-- ------------------------------------------------------------------------------------------ -->
            <div class="container">
            <h2>Select Options</h2>
            <form>
                <div class="form-group">
                    <label for="country">Departamento:</label>
                    <select class="form-control" id="country" name="country">
                        <option value="">Select Country</option>
                        @foreach($departments as $departmentuno)
                            <option value="{{ $departmentuno->id }}">{{ $departmentuno->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="state">State:</label>
                    <select class="form-control" id="state" name="state">
                        <option value="">Select State</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <select class="form-control" id="city" name="city">
                        <option value="">Select City</option>
                    </select>
                </div>
            </form>
        </div>

            <!-- ------------------------------------------------------------------------------------------ -->

            
            <!--  seccion para insertar imagenes y visualizarlos-->
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <strong>Adjuntos</strong>
                <div class="form-control border-0 bg-light shadow-sm">
                    <input type="file" id="fileInput" name="image[]" multiple accept="image/*">                                               
                </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <button type="submit" id="submitBtn" class="btn btn-primary">Crear</button>
            </div>
            <div id="responseMessage"></div>                         
            <div class="row" id="imagePreview"></div>            
            <div id="error-container"></div>

<!-- Modal para alerta de perfil incompleto -->
<div class="modal" id="depart" tabindex="-5" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Perfil Incompleto</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Para continuar, por favor complete sus datos de registro.</p>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> -->
        <!-- Aquí puedes agregar un botón para redirigir al usuario a la página de asignación de departamento -->
        <a href="{{ route('user.edit', auth()->user()->id) }}" class="btn btn-primary">Ir a Perfil</a>
      </div>
    </div>
  </div>
</div>
<!-- scrip para el nuevo select option -->
<script type="text/javascript">
$(document).ready(function() {
    $('#country').on('change', function() {
        var countryID = $(this).val();
        if (countryID) {
            $.ajax({
                url: '/get-area/' + countryID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#state').empty();
                    $('#state').append('<option value="">Select State</option>');
                    $.each(data, function(key, value) {
                        $('#state').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#state').empty();
            $('#state').append('<option value="">Select State</option>');
            $('#city').empty();
            $('#city').append('<option value="">Select City</option>');
        }
    });

    $('#state').on('change', function() {
        var stateID = $(this).val();
        if (stateID) {
            $.ajax({
                url: '/get-category/' + stateID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                    $.each(data, function(key, value) {
                        $('#city').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">Select City</option>');
        }
    });
});
</script>
<!--  -->
<?php $department_id = auth()->user()->department_id;?>

 <script>

    // script para ejecutar modal de alerta
    $(document).ready(function() {
    // Verificar si el departamento está asignado
    @if(!$department_id )
      // Mostrar el modal si el departamento no está asignado
      $('#depart').modal('show');
    @endif
  });

    // funcion para capturar el area y clasifar las categorias pertenecientes a las areas
    
    $(document).ready(function () {
        $('#area').change(function () {
            var area_id = $(this).val();
            // console.log(area_id);
            $.get("{{route('ticket.getCategory')}}", {area_id: area_id}, function (data) {
                $('#category').empty();
                $('#category').append('<option value="">Seleccionar una categoría</option>');
                $.each(data, function (index, category) {
                    $('#category').append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            });
            // console.log(category);
        });
    }); 
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
                document.getElementById('submitBtn').setAttribute('disabled', 'true'); 
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
                    habilitarEnvio();
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
                   console.log(errors);
                    habilitarEnvio();
                    
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

            function habilitarEnvio() {
                    document.getElementById('submitBtn').removeAttribute('disabled');
                }
                            


         </script>


            
        </div>
    </form>
</div>
@endsection