@extends('adminlte::page')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- @can('view',$ticket) -->

<div class="container bg-white shadow rounded" style="padding: 1%; border: 1px solid #adb5bd47;">
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

<!-- ---------------------------------------------------- -->
<div class="container mt-5">
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header">
            <h3 class="card-title">Historial</h3>
            <div class="card-tools">
                <span title="3 New Messages" class="badge badge-primary">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="direct-chat-messages" id="chat-messages">
                <!-- Messages will be appended here -->
            </div>
            <div class="direct-chat-contacts">
                <ul class="contacts-list" id="contacts-list">
                    <!-- Contacts will be appended here -->
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- ---------------------------------------------------- -->
<!-- seccion para ver el historial de gestiones -->


<!-- con mejor vista  -->
<div class="container card direct-chat direct-chat-primary">
        <div class="card-header">
        <h3 class="card-title">Historial</h3>
            <div class="card-tools">
             <span title="3 New Messages" class="badge badge-primary">3</span>
                <!--<button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
                </button> -->
            </div>
        </div>

    <div id="gestiones-container1" >
            <!-- El contenido se actualizará dinámicamente aquí -->
    </div>
</div>
<!--  -->


<div id="gestiones-container" class="container bg-white shadow rounded mt-0" style="padding: 1%; border: 1px solid #adb5bd47;">
        <!-- El contenido se actualizará dinámicamente aquí -->
</div>

<!--  -->

<!-- fin de la seccion del historico de gestiones -->

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


<form action="{{route('gestion.store')}}" id="gestion" method="POST" enctype="multipart/form-data" style=" padding: 1%; border: 1px solid #adb5bd47;"class=" container bg-white shadow rounded rounded" >
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
        $(document).ready(function() {
            function loadGestiones() {
                $.ajax({
                    url: "{{ route('tickets.gestiones', ['ticket' => $ticket->id]) }}",
                    method: 'GET',
                    success: function(data) {
                        var gestionesHtml = '<h4>Historial <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">+ ' + data.length + '</span></h4>';
                        gestionesHtml += '<div class="overflow-auto p-3" style="max-width: 100%; max-height: 300px;">';

                        data.forEach(function(gestion, index) {
                            gestionesHtml += '<div class="row rounded" style="padding: 1%; border: 1px solid #adb5bd47; ' + (index % 2 === 0 ? 'background-color: #f8f9fa;' : '') + '">';
                            gestionesHtml += '<div class="col-md-12 mt-2 rounded ' + (index % 2 === 0 ? 'ml-0' : 'ml-3') + '">';
                            gestionesHtml += '<div class="d-flex w-100 justify-content-between">';
                            gestionesHtml += '<h5 class="mb-1">' + gestion.usuario.name + '</h5>';
                            gestionesHtml += '<small class="text-success">' + moment(gestion.created_at).fromNow() + '</small>';
                            gestionesHtml += '</div>';
                            gestionesHtml += '<div class="d-flex justify-content-between">';
                            gestionesHtml += '<p>' + gestion.coment + '</p>';
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

                            gestionesHtml += '</div></div>';
                        });

                        gestionesHtml += '</div>';
                        $('#gestiones-container').html(gestionesHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading gestiones:', error);
                    }
                });
            }

            // Load gestiones initially
            loadGestiones();

            // Reload gestiones every 5 seconds
            setInterval(loadGestiones, 5000); // 5000 ms = 5 seconds
        });
    </script>
    <!-- llllllllllllllllllllllllllllllllllllllllllllllllllllll -->
    
<script>
        $(document).ready(function() {
            function loadGestiones() {
                $.ajax({
                    url: "{{ route('tickets.gestiones', ['ticket' => $ticket->id]) }}",
                    method: 'GET',
                    success: function(data) {
                        // var gestionesHtml = '<h4>Historial <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">+ ' + data.length + '</span></h4>';
                        // gestionesHtml += '<div class="overflow-auto p-3" style="max-width: 100%; max-height: 300px;">';
                        var gestionesHtml = '';

                        data.forEach(function(gestion, index) {
                            gestionesHtml += '<div class="direct-chat-msg" >';
                                gestionesHtml += '<div class="direct-chat-infos clearfix ">';
                                    gestionesHtml += '<span class="direct-chat-name float-left">' + gestion.usuario.name + '</span>';
                                    gestionesHtml += '<span class="direct-chat-timestamp float-right">' + moment(gestion.created_at).fromNow() + '</span>';
                                gestionesHtml += '</div>';
                                gestionesHtml += '<img class="direct-chat-img" src="/storage/images/user/'+gestion.usuario.image+'" alt="'+ gestion.usuario.id +'">';
                                // gestionesHtml += gestion.usuario.image;
                                gestionesHtml += '<div class="direct-chat-text">';
                                    gestionesHtml += gestion.coment;
                                gestionesHtml += '</div>';
                            gestionesHtml += '</div>';
                            
                            // console.log(gestion);

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

                            gestionesHtml += '</div></div>';
                        });
                        

                        gestionesHtml += '</div>';
                        $('#gestiones-container1').html(gestionesHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading gestiones:', error);
                    }
                });
            }

            
            // Load gestiones initially
            loadGestiones();

            // Reload gestiones every 5 seconds
            setInterval(loadGestiones, 5000); // 5000 ms = 5 seconds
        });
    </script>
<!-- <span class="direct-chat-name float-${message.user_id == {{ Auth::id() }} ? 'right' : 'left'}">${message.user.name}</span> -->
    <!-- ------------------------------------------- -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        // Fetch messages from server
        $.ajax({
            url:"{{ route('tickets.gestiones', ['ticket' => $ticket->id]) }}",
            method: 'GET',
            success: function(data) {
                // Clear existing messages
                $('#chat-messages').empty();
                console.log(data);
                // Iterate over each message and append to the chat
                data.forEach(function(h_gestiones) {
                    var messageHtml =`
                        <div class="direct-chat-msg ${h_gestiones.user_id == {{ Auth::id() }} ? 'right' : ''}">
                            <div class="direct-chat-infos clearfix">                                
                                <span class="direct-chat-timestamp float-${h_gestiones.user_id == {{ Auth::id() }} ? 'left' : 'right'}">${h_gestiones.created_at}</span>
                            </div> 
                            <div class="direct-chat-text">
                                ${g_gestiones.coment}
                            </div>                           
                        </div>`;
                    
                    $('#chat-messages').append(messageHtml);
                });
            },
            error: function(error) {
                console.error('Error fetching messages:', error);
            }
        });

        // Optionally, fetch contacts in a similar manner
        // $.ajax({
        //     url: "{{ route('gestion.ticket', ['ticket' => $ticket->id]) }}",
        //     method: 'GET',
        //     success: function(data) {
        //         // Iterate over each contact and append to the contacts list
        //         console.log(data);
        //     },
        //     error: function(error) {
        //         console.error('Error fetching contacts:', error);
        //     }
        // });
    });
</script>

<!-- ---------------------------------------------------------  -->
<!-- @endcan     -->
@endsection