@extends('adminlte::page')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- @can('view',$ticket) -->

<div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Alerta </strong> Algo fue mal..<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
</div>
<div class="container-fluid bg-white shadow rounded" style="padding: 1%; border: 1px solid #adb5bd47;">
    <div class="row">
        <div class="col-md-12 mt-2">
            <h3 class="text-leftb">Ticket # {{$ticket->id}}
                @if($ticket->status_id == 4)
            <span class="position-relative top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{$ticket->status->name}}
                @endif

            </span>
            </h3>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <!-- <label for=""><strong>Usuario solicitante:</strong></label> -->
                <h6><strong>Solicitante:</strong></h6>
                <span>{{$ticket->usuario->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Departamento:</strong></h6>
                <span>{{$ticket->usuario->department->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Area: </strong>{{$ticket->area->name}}</h6>
                <!-- <span>{{$ticket->area->name}}</span> -->
                <span>{{$ticket->category->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Ext: </strong>{{$ticket->usuario->extension}}</h6>
                
                <span><strong class="text-danger">{{$ticket->usuario->sucursal->name}}</strong></span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Titulo:</strong></h6>
                <span>{{$ticket->title}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9  rounded">
            <div class="form-group">
                <h6><strong>Descripcion:</strong></h6>
                <p class="rounded" style="background-color: #e9ecef3b;">{{$ticket->description}}</p>
            </div>
        </div>

        @if(!empty($ticket->image))
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="image"><strong>Imágenes:</strong></label>
                    <br>
                    <ul class="row " style="padding-right:40px ;">
                        @foreach(explode(',', $ticket->image) as $imageItem )
                            <li class="list-group-item   border border-3 col-lg-3 col-md-6 col-sm-12 rounded"><a href="{{asset('storage/images/'. $imageItem)}}" target="_blank" alt="{{ $ticket->id }}">{{$imageItem}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

    </div>
</div>


<!-- vista resumida -->
    <div class="container-fuid">
        <div class="card direct-chat direct-chat-primary">
            <!-- <div class="card-header"> -->
                <!-- <h3 class="card-title">Historial</h3> -->
                <!-- <div class="card-tools"> -->
                    <!-- <span title="3 New Messages" class="badge badge-primary">3</span> -->
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse"> -->
                        <!-- <i class="fas fa-minus"></i> -->
                    <!-- </button> -->
                <!-- </div> -->
            <!-- </div> -->
            <!-- <div class="card-body"> -->
                <div class="direct-chat-msg" id="gestiones-container1">
                    <!-- Messages will be appended here -->
                </div>
            <!-- </div> -->
        <!-- <div class="card-footer">
            <form action="#" method="post">
                <div class="input-group">
                    <input class="form-group" type="file" id="fileInput"  accept="image/*" multiple>
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                    <button type="button" class="btn btn-primary">Send</button><span>add</span>
                    </span>
                </div>
            </form>
        </div> -->
        <!-- <div class="card-footer">
            <form action="#" method="post">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary">Send</button>
                        <button type="button" class="btn btn-secondary" id="addImageButton"><i class="fas fa-image"></i></button>
                        <button type="button" class="btn btn-secondary" id="captureScreenButton"><i class="fas fa-camera"></i></button>
                    </span>
                </div>
            </form>
            <input type="file" id="fileInput" accept="image/*" multiple style="display: none;">
        </div> -->
        <div class="card-footer">
            <form id="messageForm">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="messageInput">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary" id="sendMessageButton">Send</button>
                        <button type="button" class="btn btn-secondary" id="addImageButton"><i class="fas fa-image"></i></button>
                        <button type="button" class="btn btn-secondary" id="captureScreenButton"><i class="fas fa-camera"></i></button>
                    </span>
                </div>
                <input type="file" id="fileInput" accept="image/*" multiple style="display: none;">
            </form>
            <div id="imagePreviewContainer" class="mt-3"></div>
        </div>

        </div>
    </div>


<!-- fin de la seccion del historico de gestiones -->

<!-- -------------------------------------- -->

<!-- ------------------------------------------ -->


<form action="{{route('gestion.store')}}" id="gestion" method="POST" enctype="multipart/form-data" style=" padding: 1%; border: 1px solid #adb5bd47;" class=" container-fluid bg-white shadow rounded rounded" >
    <!-- <h4>Gestionar</h4>  -->
        @csrf
        <input type="hidden" name="ticket_id" class="form-control" value="{{$ticket->id}}" >
        <!-- <input type="text" name="staff" class="form-control" value="{{$ticket->user_id}}" > -->
        <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" >
        <input type="hidden" name="status1_id" class="form-control" value="{{$ticket->status_id}}" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Agregar comentario:</strong>
                    <textarea class="form-control" style="height:150px" name="coment" require></textarea>
                    </div>
            </div>
            
           <!--  -->
            
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                <div class="form-group">
                    <strong>Area:</strong>
                    <select name="area_id" id="area" class="form-control border-0 bg-light shadow-sm ">
                    <option value="">Seleccionar un Area</option>
                    @foreach($areas as  $id => $name)
                    <option value="{{$id}}" @if($id == old('area_id' , $ticket->area_id)) selected @endif >{{$name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                <div class="form-group">
                    <strong>Categoria:</strong>
                    <select name="category_id" id="category" class="form-control border-0 bg-light shadow-sm " >
                        <option value=""> Seleccionar categoria</option>
                        @foreach($category as $id => $name)
                        <option value="{{ $id }}" {{ $ticket->category_id == $id ? 'selected' : '' }} >{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            

            <!--  -->
            <div class="col-xs-12 col-sm-12 col-md-4 mt-2 d-flex justify-content-end">
                @if($ticket->status_id != 4)            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="cerrar" {{ $ticket->status_id == 4 ? 'checked' : '' }}>
                    <label class="form-check-label text-danger" for="status_id"><strong>Cerrar Ticket</strong></label>            
                </div>
                @endif
                @if($ticket->status_id == 4)            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2" name="reopen" {{ $ticket->status_id == 4 ? 'checked' : '' }}>
                    <label class="form-check-label text-success" for="status_id"><strong>Reabrir Ticket</strong></label>            
                </div>
                @endif
                
            </div>           
            <!--  seccion para insertar imagenes y visualizarlos-->

            <!--  -->
            <div  style="padding: 1%;">
            <input class="form-group" type="file" id="fileInput" name="image[]" accept="image/*" multiple>
            </div>
            <div id="previewImages"></div>

            
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <a  class="btn btn-primary" href="{{route('ticket.index')}}" >Cancelar</a>
                <button type="submit" id="submitGt" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
    </div>
    </div>
 @endsection
 
 @section('js')
<!-- ---------------------------------------------------- -->
<script>
        // 
        $(document).ready(function () {
            $('#area').change(function () {
                var area_id = $(this).val();
                console.log(area_id);
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

        const fileInput = document.getElementById('fileInput');
        const previewImages = document.getElementById('previewImages');

        fileInput.addEventListener('change', function() {
            previewImages.innerHTML = ''; // Limpiar cualquier vista previa existente

            const files = fileInput.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    previewImages.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <!--  -->
<!-- -------------------------------------------------------- -->

    <script>
    document.getElementById('gestion').addEventListener('submit', function() {
        document.getElementById('submitGt').setAttribute('disabled', 'true');
    });
    </script>
<!--  -->
    
    <script>
        function formatDate(format, dateString) {
            const date = new Date(dateString);

            const map = {
                'hh': String(date.getHours()).padStart(2, '0'),
                'mm': String(date.getMinutes()).padStart(2, '0'),
                'ss': String(date.getSeconds()).padStart(2, '0'),
                'yyyy': date.getFullYear(),
                'MM': String(date.getMonth() + 1).padStart(2, '0'),
                'dd': String(date.getDate()).padStart(2, '0')
            };

            return format.replace(/hh|mm|ss|yyyy|MM|dd/g, matched => map[matched]);
        }

        $(document).ready(function() {
            $.ajax({
                url: "{{ route('tickets.gestiones', ['ticket' => $ticket->id]) }}",
                method: 'GET',
                success: function(data) {
                    var gestionesHtml = '';
                    gestionesHtml += '<div class="card direct-chat direct-chat-primary">';
                    var userId = {{ Auth::id() }}; // Obtiene el ID del usuario logueado

                    

                    gestionesHtml += '<div class="card-header">';
                    gestionesHtml += '<h3 class="card-title">Historial </h3>';
                    gestionesHtml += '<div class="card-tools">';
                    gestionesHtml +='<span title="3 New Messages" class="badge badge-primary">+ ' + data.length + '</span>';
                    gestionesHtml += '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>';
                    gestionesHtml += '</div>';
                    gestionesHtml += '</div>';
                    
                    gestionesHtml += '<div class="card-body">';
                    gestionesHtml += '<div class="direct-chat-messages">';

                    if (data.length > 0) {

                    data.forEach(function(gestion) {
                        var isCurrentUser = gestion.usuario.id === userId;

                        gestionesHtml += '<div class="direct-chat-msg ' + (isCurrentUser ? 'right' : '') + '">';
                        gestionesHtml += '<div class="direct-chat-infos clearfix">';
                        gestionesHtml += '<span class="direct-chat-name float-' + (isCurrentUser ? 'right' : 'left') + '">' + gestion.usuario.name + '</span>';
                        gestionesHtml += '<span class="direct-chat-timestamp float-' + (isCurrentUser ? 'left' : 'right') + '">' + formatDate('hh:mm:ss',gestion.created_at) + '</span>';
                        gestionesHtml += '</div>';
                        if(gestion.usuario.image){
                            gestionesHtml += '<img class="direct-chat-img" src="/storage/images/user/' + gestion.usuario.image + '" alt="' + gestion.usuario.id + '">';
                        } 
                        gestionesHtml += '<div class="direct-chat-text">';
                        gestionesHtml += gestion.coment;
                        gestionesHtml += '</div>';
                        gestionesHtml += '</div>';

                        if (gestion.image) {
                            var images = gestion.image.split(',');
                            gestionesHtml += '<div class="form-group">';
                            gestionesHtml += '<strong>Adjunto</strong>';
                            images.forEach(function(image) {
                                gestionesHtml += '<a href="/storage/images/' + image + '" target="_blank">';
                                gestionesHtml += '<ul><li>' + image + '</li></ul>';
                                gestionesHtml += '</a>';
                            });
                            gestionesHtml += '</div>';
                        }
                    });
                    
                    }else{
                        gestionesHtml += '<p class="text-center">No hay gestiones para mostrar</p>';
                    }
                    gestionesHtml += '</div>';
                    gestionesHtml += '</div>';
                    gestionesHtml += '</div>';

                    $('#gestiones-container1').html(gestionesHtml);
                    // Reinicializar los componentes de AdminLTE
                    // $('[data-card-widget="collapse"]').CardWidget('init');
                    // Colapsar la tarjeta si no hay gestiones
                    if (data.length === 0) {
                        $('[data-card-widget="collapse"]').CardWidget('collapse');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading gestiones:', error);
                }
            });            
                // Configura el intervalo de actualización
                setInterval(function() {
                table.ajax.reload(null, false); // false para no resetear la posición de la paginación
                }, 10000); // 5000 ms = 5 segundos
        });
    </script>
   

<!-- ---------------------------------------------------------  -->
<script>
    $(document).ready(function() {
        // Botón para agregar imágenes
        $('#addImageButton').on('click', function() {
            $('#fileInput').click();
        });

        // Botón para capturar pantalla (este es un ejemplo simple, puedes implementar una captura de pantalla más avanzada según tus necesidades)
        $('#captureScreenButton').on('click', function() {
            alert('Función de captura de pantalla no implementada');
            // Aquí podrías implementar la lógica para capturar la pantalla
            // Hay librerías como html2canvas que pueden ayudarte con esto
        });

        // Manejar el evento de selección de archivos
        $('#fileInput').on('change', function() {
            var files = $(this).prop('files');
            // Aquí puedes manejar los archivos seleccionados
            console.log(files);
        });
    });
</script>
<!-- @endcan     -->

<script>
$(document).ready(function() {
    // Botón para agregar imágenes
    $('#addImageButton').on('click', function() {
        $('#fileInput').click();
    });

    // Botón para capturar pantalla (placeholder, puedes implementar según tus necesidades)
    $('#captureScreenButton').on('click', function() {
        alert('Función de captura de pantalla no implementada');
        // Aquí podrías implementar la lógica para capturar la pantalla
        // Librerías como html2canvas pueden ayudarte con esto
    });

    // Manejar el evento de selección de archivos
    $('#fileInput').on('change', function() {
        handleFiles(this.files);
    });

    // Manejar el evento de pegado en el input
    $('#messageInput').on('paste', function(event) {
        handlePaste(event);
    });

    // Enviar el mensaje
    $('#sendMessageButton').on('click', function() {
        sendMessage();
    });

    function handleFiles(files) {
        var container = $('#imagePreviewContainer');
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = $('<img>').attr('src', e.target.result);
                    container.append(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function handlePaste(event) {
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        var container = $('#imagePreviewContainer');
        for (var index in items) {
            var item = items[index];
            if (item.kind === 'file') {
                var blob = item.getAsFile();
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = $('<img>').attr('src', e.target.result);
                    container.append(img);
                };
                reader.readAsDataURL(blob);
            }
        }
    }

    function sendMessage() {
        var message = $('#messageInput').val();
        var files = $('#fileInput')[0].files;

        if (message || files.length > 0 || $('#imagePreviewContainer img').length > 0) {
            var formData = new FormData();
            formData.append('message', message);
            Array.from(files).forEach(file => {
                formData.append('images[]', file);
            });

            // Agregar las imágenes pegadas
            $('#imagePreviewContainer img').each(function(index, img) {
                var blob = dataURLToBlob($(img).attr('src'));
                formData.append('pastedImages[]', blob, 'pastedImage' + index + '.png');
            });

            $.ajax({
                url: 'your-server-endpoint', // Reemplaza con tu endpoint de servidor
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#messageInput').val('');
                    $('#fileInput').val('');
                    $('#imagePreviewContainer').empty();
                    // Aquí puedes manejar la respuesta del servidor
                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                }
            });
        }
    }

    function dataURLToBlob(dataURL) {
        var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type:mime});
    }
});


</script>
@endsection

@section('css')
<style>
 #imagePreviewContainer img {
    max-width: 100px;
    max-height: 100px;
    margin: 5px;
}   
</style>
@endsection