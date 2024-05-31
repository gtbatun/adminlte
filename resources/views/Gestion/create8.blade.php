@extends('adminlte::page')
@section('content')

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
            <h3 class="text-left">Ticket # {{$ticket->id}}
                @if($ticket->status_id == 4)
                    <span class="badge rounded-pill bg-danger">{{$ticket->status->name}}</span>
                @endif
            </h3>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
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
        <div class="col-xs-12 col-sm-12 col-md-9 rounded">
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
                    <ul class="row" style="padding-right:40px;">
                        @foreach(explode(',', $ticket->image) as $imageItem )
                            <li class="list-group-item border border-3 col-lg-3 col-md-6 col-sm-12 rounded">
                                <a href="{{asset('storage/images/'. $imageItem)}}" target="_blank">{{$imageItem}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <div class="container-fluid">
        <div class="card direct-chat direct-chat-primary">
            <div class="card-header">
                <h3 class="card-title">Historial</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" title="Contacts">
                        <i class="fas fa-comments"></i>
                        <div class="badge rounded-pill bg-primary" id="data-length"></div>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="direct-chat-messages" id="gestiones-container1">
                    <!-- Messages will be appended here -->
                </div>
            </div>
            <div class="card-footer">
                <form id="gestionform" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ticket_id" class="form-control" value="{{$ticket->id}}">
                    <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}">
                    <div id="errorContainer"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                            <div class="form-group">
                                <strong>Area:</strong>
                                <select name="area_id" id="area" class="form-control border-1 bg-light shadow-sm">
                                    <option value="">Seleccionar un Area</option>
                                    @foreach($areas as $id => $name)
                                        <option value="{{$id}}" @if($id == old('area_id', $ticket->area_id)) selected @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 mt-2">
                            <div class="form-group">
                                <strong>Categoria:</strong>
                                <select name="category_id" id="category" class="form-control border-1 bg-light shadow-sm">
                                    <option value="">Seleccionar categoria</option>
                                    @foreach($category as $id => $name)
                                        <option value="{{ $id }}" {{ $ticket->category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 pb-2 d-flex justify-content-end">
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
                    </div>
                    <div class="input-group">
                        <textarea name="coment" placeholder="Type Message ..." class="form-control" id="messageInput" rows="1"></textarea>
                        <span class="input-group-append">
                            <button type="button" class="btn btn-primary" id="sendMessageButton">Send</button>
                            <button type="button" class="btn btn-secondary" id="addImageButton"><i class="fas fa-image"></i></button>
                        </span>
                    </div>
                    <input type="file" name="image[]" id="fileInput" accept="image/*" multiple style="display: none;">
                </form>
                <div id="imagePreviewContainer" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
    const messageInput = document.getElementById('messageInput');

    messageInput.addEventListener('input', function() {
        this.style.height = 'auto'; // Resetea la altura
        this.style.height = (this.scrollHeight) + 'px'; // Ajusta la altura según el contenido
    });

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

    function loadGestiones() {
        $.ajax({
            url: "{{ route('tickets.gestiones', ['ticket' => $ticket->id]) }}",
            method: 'GET',
            success: function(data) {
                const gestionesContainer = $('#gestiones-container1');
                let gestionesHtml = '';
                const userId = {{ Auth::id() }}; // Obtiene el ID del usuario logueado  

                if (data.length > 0) {
                    data.forEach(function(gestion) {
                        const isCurrentUser = gestion.usuario.id === userId;
                        gestionesHtml += `
                            <div class="direct-chat-msg ${isCurrentUser ? 'right' : ''}">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-${isCurrentUser ? 'right' : 'left'}">${gestion.usuario.name}</span>
                                    <span class="direct-chat-timestamp float-${isCurrentUser ? 'left' : 'right'}">${formatDate('hh:mm:ss', gestion.created_at)}</span>
                                </div>
                                <img class="direct-chat-img" src="/storage/images/user/${gestion.usuario.image || 'default.PNG'}" alt="${gestion.usuario.id}" onerror="this.src='/storage/images/user/default.PNG'">
                                <div class="direct-chat-text">${gestion.coment}</div>
                            </div>`;

                        if (gestion.image) {
                            const images = gestion.image.split(',');
                            gestionesHtml += '<div class="form-group"><strong>Adjunto</strong>';
                            images.forEach(function(image) {
                                gestionesHtml += `<a href="/storage/images/${image}" target="_blank"><ul><li>${image}</li></ul></a>`;
                            });
                            gestionesHtml += '</div>';
                        }
                    });
                } else {
                    gestionesHtml += '<p class="text-center">No hay gestiones para mostrar</p>';
                }

                gestionesContainer.html(gestionesHtml);
                $('#data-length').text(data.length);
            },
            error: function(xhr, status, error) {
                console.error('Error loading gestiones:', error);
            }
        });            
    }

    function validateForm() {
        let isValid = true;
        let errorMessage = '';

        const area_id = $('select[name="area_id"]').val();
        const category_id = $('select[name="category_id"]').val();
        const coment = $('textarea[name="coment"]').val();

        if (!area_id) {
            isValid = false;
            errorMessage += 'El campo "Area" es obligatorio.\n';
        }
        if (!category_id) { 
            isValid = false;
            errorMessage += 'El campo "Categoria" es obligatorio.\n';
        }
        if (!coment) {
            isValid = false;
            errorMessage += 'El campo "Mensaje" es obligatorio.\n';
        }

        if (!isValid) {
            $('#errorContainer').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        } else {
            $('#errorContainer').empty(); // Limpiar cualquier mensaje de error previo
        }

        return isValid;
    }

    function sendMessage() {
        if (!validateForm()) {
            return; // Detener el envío si el formulario no es válido
        }
        const formData = new FormData($('#gestionform')[0]);
        $('#imagePreviewContainer img').each(function(index, img) {
            const blob = dataURLToBlob($(img).attr('src'));
            formData.append('pastedImages[]', blob, `pastedImage${index}.png`);
        });
        $.ajax({
            url: "{{ route('gestion.store') }}", // Reemplaza con tu endpoint de servidor
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#messageInput').val('');
                $('#fileInput').val('');
                $('#imagePreviewContainer').empty();
                loadGestiones();
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
                $('#errores').append(error);
            }
        });
    }

    function dataURLToBlob(dataURL) {
        const arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
              bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type: mime});
    }

    // Initial load
    // loadGestiones();

    // Event handlers
    $('#sendMessageButton').on('click', function() {
        if (!$(this).prop('disabled')) { 
            sendMessage();
            $(this).prop('disabled', true); 
            setTimeout(() => $('#sendMessageButton').prop('disabled', false), 3000);
        }
    });

    $('#addImageButton').on('click', function() {
        $('#fileInput').click();
    });

    $('#fileInput').on('change', function() {
        handleFiles(this.files);
    });

    $('#messageInput').on('paste', function(event) {
        handlePaste(event);
    });

    $('#area').change(function() {
        const area_id = $(this).val();
        $.get("{{ route('ticket.getCategory') }}", { area_id }, function(data) {
            const category = $('#category');
            category.empty();
            category.append('<option value="">Seleccionar una categoría</option>');
            data.forEach(category => {
                category.append(`<option value="${category.id}">${category.name}</option>`);
            });
        });
    });

    function handleFiles(files) {
        const container = $('#imagePreviewContainer');
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                    container.append(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function handlePaste(event) {
        const items = (event.clipboardData || event.originalEvent.clipboardData).items;
        const container = $('#imagePreviewContainer');
        for (const item of items) {
            if (item.kind === 'file') {
                const blob = item.getAsFile();
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                    container.append(img);
                };
                reader.readAsDataURL(blob);
            }
        }
    }
});
</script>


@endsection

@section('css')
<style>
 #imagePreviewContainer img {
    max-width: 200px;
    max-height: 200px;
    margin: 5px;
}  
 /* textarea enviar  */
 #messageInput {
    overflow-y: hidden; /* Oculta el scroll vertical */
    resize: none; /* Desactiva la opción de redimensionar manualmente */
}
</style>
@endsection
