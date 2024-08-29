<!-- Modal para gestionar los tickets -->
<div class="modal fade" id="modal-gestion-ticket" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">  
        <!-- modal-lg  modal-fullscreen-xxl-->
        <div class="modal-content">
            <div class="modal-header">
                <div class="form-group" >
                <h5 class="modal-title">Gestionar ticket </h5>
                <strong class="text-danger"><span id="ticket-name-title"></span></strong>
                
                </div>                
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="ticket-description"></p>                
                        <div class="container-fuid">
                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header">
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" title="Contacts">
                                    <i class="fas fa-comments"></i>
                                    <div class="float-right badge rounded-pill bg-primary"  id="data-length" ></div>
                                </button>
                            </div>            
                        </div>            
                        <div class="card-body">
                            <div class="direct-chat-messages" id="gestiones-container1">
                                <!-- Messages will be appended here -->
                            </div>
                        </div>      
                        <div class="card-header">
                            <form id="gestionform" method="POST" enctype="multipart/form-data">
                                @csrf                        
                                <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{auth()->user()->id}}" >
                                <input type="hidden"  class="form-control" id="ticket-id" name="ticket_id">
                                <div id="errorContainer" ></div>
                                <div class="row"> 
                                    <!-- inicio seccion de area y categorias -->                                
                                    <div class="col-xs-12 col-sm-12 col-md-12 mb-2 d-flex justify-content-end" > 
                                        <div class="form-check" id="cerrar" style="display: none;">
                                            <input class="form-check-input" type="checkbox" value="1" name="cerrar" >
                                            <label class="form-check-label text-danger" for="status_id"><strong>Cerrar Ticket</strong></label>            
                                        </div>                                
                                        <div class="form-check" id="reopen" style="display: none;">
                                            <input class="form-check-input" type="checkbox" value="2" name="reopen" >
                                            <label class="form-check-label text-success" for="status_id"><strong>Reabrir Ticket</strong></label>            
                                        </div>
                                    </div>                            
                                </div> 
                                <!-- fin de seccion de botones de cerrar y reabrir ticket -->
                                <div class="input-group">
                                    <textarea name="coment" placeholder="Type Message ..." class="form-control" id="messageInput" rows="2"></textarea>
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
        </div>
    </div>
</div>


<script>

$(document).ready(function() {
    var userIsAdmin = @json(auth()->user()->is_admin);
    var userDepartmentId = @json(auth()->user()->department_id);
    var ticketId; 

    // var  TicketComments = {};

    // boton desde mi tabla y con datos necesarios a mostarar en el modal
    $(document).on('click','.notification-btn, .modal-gestion-btn',function(){
        ticketId = $(this).data('ticket-id');
        console.log('iniciando: '+ticketId);

        // Guardar el comentario actual si existe
        // var currentTicketId =$(this).find('#ticket-id').val();
        // var currentTicketComment = $(this).find('#messageInput').val();
        // if(currentTicketId) {
        //     TicketComments[currentTicketId] = currentTicketComment;
        // }

        

        // Rellenar el comentario si existe para el dispositivo seleccionado
        // if (TicketComments[currentTicketId]) {
        //     $(this).find('#messageInput').val(TicketComments[currentTicketId]);
        // }else{
        //     $(this).find('#messageInput').val('');
        // }

        

    if ($(this).hasClass('modal-gestion-btn')) {
        ticketId = $(this).data('ticket-id');
        var ticketTitle = $(this).data('ticket-title');            
        var ticketDescription = $(this).data('ticket-description');
        var ticketStatus = $(this).data('ticket-status');
        var ticketDepartmetId = $(this).data('ticket-department-id');

        $('#modal-gestion-ticket').find('#ticket-id').val(ticketId);            
        $('#modal-gestion-ticket').find('#ticket-name-title').text(ticketTitle);
        $('#modal-gestion-ticket').find('#ticket-description').text(ticketDescription);   
        $('#messageInput').val('');
           

        handleTicketStatus(ticketStatus, ticketDepartmetId);
        // 
        $.ajax({
            url: "{{ route('notifications.markAsRead') }}",
                method: 'POST',
                data: {
                    ticket_id: ticketId,
                    _token: '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                },
                success: function(response) {
                    /// talvez agregar que recargue la pagina o algo chido
                    $('#modal-gestion-ticket').modal('show');
                    $('#tickets-table').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error marking notifications as read:', error);
                }
            });
            // 

    } else if ($(this).hasClass('notification-btn')) {
        // ticketId = $(this).data('ticket-id');
        console.log('dentro del else: '+ticketId);
        

            $.ajax({
                url: "{{ route('notifications.markAsRead') }}",
                method: 'POST',
                data: {
                    ticket_id: ticketId,
                    _token: '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                },
                success: function(response) {
                    
                    // Obtener los detalles del ticket si es necesario
                    /**** */
                    // $.ajax({
                    //     url: '/tickets/' + ticketId + '/details',
                    //     method: 'GET',
                    //     success: function(ticket) {
                    //         // console.log(ticket);
                    //         // Asignar los datos al modal
                    //         $('#modal-gestion-ticket').find('#ticket-id').val(ticket.id);            
                    //         $('#modal-gestion-ticket').find('#ticket-name-title').text(ticket.title);
                    //         $('#modal-gestion-ticket').find('#ticket-description').text(ticket.description);
                    //         handleTicketStatus(ticket.status_id, ticket.department_id);
                    //         // Mostrar el modal
                    //         $('#modal-gestion-ticket').modal('show');
                    //         updateNotificationCount();
                    //     },
                    //     error: function(xhr, status, error) {
                    //         console.error('Error fetching ticket details:', error);
                    //     }
                    // });

                    /*** */
                    // $('#modal-gestion-ticket').modal('show');

                },
                error: function(xhr, status, error) {
                    console.error('Error marking notifications as read:', error);
                }
            });
        
        
    }

        // loadGestiones();
        // $('#modal-gestion-ticket').modal('show');

        $(document).ready(function() {
            loadGestiones();             
            setInterval(loadGestiones, 60000);  
        });

    });

    // Manejar el cambio de área para actualizar las categorías
    $('#area').change(function () {
        var areaId = $(this).val();
        updateCategories(areaId);
    });

    // Enviar el mensaje
    $('#sendMessageButton').on('click', function() {
        if (!$(this).prop('disabled')) {
            sendMessage();
            $(this).prop('disabled', true);
            setTimeout(function() {
                $('#sendMessageButton').prop('disabled', false);
            }, 3000);
        }
    });

    // Botón para agregar imágenes
    $('#addImageButton').on('click', function() {
        $('#fileInput').click();
    });

    // Manejar el evento de selección de archivos
    $('#fileInput').on('change', function() {
        handleFiles(this.files);
    });

    // Manejar el evento de pegado en el input
    $('#messageInput').on('paste', function(event) {
        handlePaste(event);
    });

    // Función para manejar el estado del ticket
    function handleTicketStatus(ticketStatus, ticketDepartmetId) {
        $('#cerrar, #reopen').hide();
        if (userIsAdmin == 10 || userDepartmentId == ticketDepartmetId) {            
            if (ticketStatus != 4) {
                $('#cerrar').show();
            } else {
                $('#reopen').show();
            }
        } 
    }

    // Función para cargar gestiones
    function loadGestiones() {        
        $.ajax({
            url: "/tickets/" + ticketId + "/gestiones",
            method: 'GET',
            success: function(data) {
                renderGestiones(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading gestiones:', error);
            }
        });
    }

    // Función para renderizar las gestiones
    function renderGestiones(data) {
        var gestionesHtml = ''; 
        var userId = {{Auth::id() }};
        if (data.length > 0) {
            data.forEach(function(gestion) {
                gestionesHtml += createGestionHtml(gestion, userId);
            });
            $('[data-card-widget="collapse"]').CardWidget('expand');
        } else {
            gestionesHtml = '<p class="text-center">No hay gestiones para mostrar</p>';
            $('[data-card-widget="collapse"]').CardWidget('collapse');
        }
        $('#gestiones-container1').html(gestionesHtml);    
        $('#data-length').text(data.length);
    }

    // Función para crear el HTML de una gestión
    function createGestionHtml(gestion, userId) {
        var isCurrentUser = gestion.usuario.id === userId;
        var html = '<div class="direct-chat-msg ' + (isCurrentUser ? 'right' : '') + '">';
        html += '<div class="direct-chat-infos clearfix">';
        html += '<span class="direct-chat-name float-' + (isCurrentUser ? 'right' : 'left') + '">' + gestion.usuario.name + '</span>';
        html += '<span class="direct-chat-timestamp float-' + (isCurrentUser ? 'left' : 'right') + '">' + formatDate('MM dd hh:mm:ss', gestion.created_at) + '</span>';
        html += '</div>';
        if (gestion.usuario.image) {
            html += '<img class="direct-chat-img" src="/storage/images/user/' + (gestion.usuario.image || 'default.png') + '" alt="' + gestion.usuario.id + '" onerror="this.src=\'/storage/images/user/default.PNG\'">';
        }
        html += '<div class="direct-chat-text float-' + (isCurrentUser ? 'right' : 'left') + '">' + gestion.coment + '</div>';
        html += '</div>';

        if (gestion.image) {
            html += '<div class="form-group">';
            html += '<strong>Adjunto</strong>';
            gestion.image.split(',').forEach(function(image) {
                html += '<a href="/storage/images/' + image + '" target="_blank"><ul><li>' + image + '</li></ul></a>';
            });
            html += '</div>';
        }
        return html;
    }

    // Función para actualizar las categorías basadas en el área seleccionada
    function updateCategories(areaId) {
        $.get("{{route('ticket.getCategory')}}", {area_id: areaId}, function (data) {
            $('#category').empty().append('<option value="">Seleccionar una categoría</option>');
            $.each(data, function (index, category) {
                $('#category').append('<option value="' + category.id + '">' + category.name + '</option>');
            });
        });
    }

    // Validación del formulario
    function validateForm() {
        var isValid = true;
        var errorMessage = '';
        var coment = $('textarea[name="coment"]').val();
        var ticket_id = $('input[name="ticket_id"]').val();

        if (!coment) {
            isValid = false;
            errorMessage += 'El campo "Mensaje" es obligatorio.\n';
        }

        if (!isValid) {
            $('#errorContainer').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        } else {
            $('#errorContainer').empty();
        }

        return isValid;
    }

    // Función para enviar el mensaje
    function sendMessage() {
        if (!validateForm()) return;

        var formData = new FormData($('#gestionform')[0]);
        // console.log([...formData.entries()]); // Esto imprime todos los datos que estás enviando
        // sendMessage(formData);
        $('#imagePreviewContainer img').each(function(index, img) {
            var blob = dataURLToBlob($(img).attr('src'));
            formData.append('pastedImages[]', blob, 'pastedImage' + index + '.png');
        });

        $.ajax({
            url: "{{ route('gestion.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                loadGestiones();
                $('#messageInput').val('');
                $('#fileInput').val('');
                $('#imagePreviewContainer').empty();
                // Refrescar la tabla de equipos después de guardar    
                $('#tickets-table').DataTable().ajax.reload();                
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
                alert(`Error: ${xhr.status} - ${xhr.responseText}`);
            }
        });
    }

    // Función para manejar la carga de archivos
    function handleFiles(files) {
        var container = $('#imagePreviewContainer');
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                    container.append(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Función para manejar la pegatina de imágenes
    function handlePaste(event) {
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        var container = $('#imagePreviewContainer');
        for (var index in items) {
            var item = items[index];
            if (item.kind === 'file') {
                var blob = item.getAsFile();
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                    container.append(img);
                };
                reader.readAsDataURL(blob);
            }
        }
    }

    // Función para convertir dataURL a Blob
    function dataURLToBlob(dataURL) {
        var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type: mime});
    }

    // Función para formatear la fecha
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

    /** Muestra la contidad de notificaciones sin leer dentro del ticket */ 
    function updateNotificationCount() {
        $.ajax({
            url: '{{ route("notifications.count") }}', //------------------------
            method: 'GET',
            success: function(response) {
                $('#contadorNotificacion').text(response.unread_notifications_count);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

});

</script>
    

