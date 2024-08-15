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
                                <input type="hidden"  class="form-control" id="ticket-id" >
                                <div id="errorContainer" ></div>
                                <div class="row"> 
                                    <!-- inicio seccion de area y categorias -->                                
                                    <div class="col-xs-12 col-sm-12 col-md-4 mt-1 d-flex justify-content-end" > 
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
        </div>
    </div>
</div>


<script>
    var userIsAdmin = @json(auth()->user()->is_admin);
    var userDepartmentId = @json(auth()->user()->department_id);
    var ticketId;

// manejar el boton de agregar gestion a un ticket
$(document).on('click','.modal-gestion-btn',function(){
    ticketId = $(this).data('ticket-id');
    var ticketTitle = $(this).data('ticket-title');            
    var ticketDescription = $(this).data('ticket-description');
    var ticketStatus = $(this).data('ticket-status');
    var ticketDepartmet_id = $(this).data('ticket-department-id');

    $('#modal-gestion-ticket').find('#ticket-id').val(ticketId);            
    $('#modal-gestion-ticket').find('#ticket-name-title').text(ticketTitle);
    $('#modal-gestion-ticket').find('#ticket-description').text(ticketDescription);

    if(userIsAdmin == 10 || userDepartmentId == ticketDepartmet_id ){            
        if(ticketStatus != 4){
            $('#cerrar').show();
        }else{
            $('#reopen').show();
        }
    } 
    $('#modal-gestion-ticket').modal('show');
    

    function formatDate(format, dateString) {
        const date = new Date(dateString);
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        const map = {
            'hh': String(date.getHours()).padStart(2, '0'),
            'mm': String(date.getMinutes()).padStart(2, '0'),
            'ss': String(date.getSeconds()).padStart(2, '0'),
            'yyyy': date.getFullYear(),
            'MM': monthNames[date.getMonth()],
            // 'MM': String(date.getMonth() + 1).padStart(2, '0'),
            'dd': String(date.getDate()).padStart(2, '0')
        };

        return format.replace(/hh|mm|ss|yyyy|MM|dd/g, matched => map[matched]);
    }

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
        });

    

    // $(document).ready(function() {
        loadGestiones();             
    //     setInterval(loadGestiones, 60000);  
    // });

    /** solixcitud de gestiones */
    function loadGestiones() {        
        $.ajax({
            url: "/tickets/" + ticketId + "/gestiones",
            method: 'GET',
            success: function(data) {
                dataLength = data.length;
                $('#data-length').text(dataLength);
                var gestionesHtml = ''; 
                var userId = {{Auth::id() }}; // Obtiene el ID del usuario logueado                     
                if (data.length > 0) {
                data.forEach(function(gestion) {
                    var isCurrentUser = gestion.usuario.id === userId;
                    gestionesHtml += '<div  class="direct-chat-msg ' + (isCurrentUser ? 'right' : '') + '">';
                    gestionesHtml += '<div class="direct-chat-infos clearfix">';
                    gestionesHtml += '<span class="direct-chat-name float-' + (isCurrentUser ? 'right' : 'left') + '">' + gestion.usuario.name + '</span>';
                    gestionesHtml += '<span class="direct-chat-timestamp float-' + (isCurrentUser ? 'left' : 'right') + '">' + formatDate('MM dd hh:mm:ss',gestion.created_at) + '</span>';
                    gestionesHtml += '</div>';
                    if(gestion.usuario.image){
                        gestionesHtml += '<img class="direct-chat-img" src="/storage/images/user/' + (gestion.usuario.image || 'default.png') + '" alt="' + gestion.usuario.id + '" onerror="this.src=\'/storage/images/user/default.PNG\'">';
                        } 
                    gestionesHtml += '<div class="direct-chat-text float-' + (isCurrentUser ? 'right' : 'left') + '">';
                    gestionesHtml += gestion.coment;
                    gestionesHtml += '</div>';
                    gestionesHtml += '</div>';
                    // seccion imagenes
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
                $('[data-card-widget="collapse"]').CardWidget('expand');
                }else{
                    gestionesHtml += '<p class="text-center">No hay gestiones para mostrar</p>';
                     // Si no hay gestiones, colapsa la tarjeta
                    $('[data-card-widget="collapse"]').CardWidget('collapse');
                }
                $('#gestiones-container1').html(gestionesHtml);                    
            },
            error: function(xhr, status, error) {
                console.error('Error loading gestiones:', error);
            }
        });
    }

        /** seccion para agregar las imagenes y la gestion de envio de los datos */
        // definir maximo de imagenes
        const MAX_IMAGES = 4;
        let totalImages = 0;
        // Botón para agregar imágenes
        $('#addImageButton').on('click', function() {
            $('#fileInput').click();
        });
        // Botón para capturar pantalla (placeholder, puedes implementar según tus necesidades)
        $('#captureScreenButton').on('click', function() {
            alert('Función de captura de pantalla no implementada');
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
            // sendMessage();
            if (!$(this).prop('disabled')) { // Verificar si el botón no está desactivado
                sendMessage();
                $(this).prop('disabled', true); // Desactivar el botón después de enviar el formulario
                // Reactivar el botón después de 3 segundos (3000 milisegundos)
                setTimeout(function() {
                    $('#sendMessageButton').prop('disabled', false);
                }, 3000);
            }
        });

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

        function updateImagePreviews(files) {
            const container = $('#imagePreviewContainer');
            container.empty(); // Clear previous previews

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
                        container.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        /** secccion  de validacion del formulario */
        function validateForm() {
            var isValid = true;
            var errorMessage = '';

            // var area_id = $('select[name="area_id"]').val();
            // var category_id = $('select[name="category_id"]').val();
            var coment = $('textarea[name="coment"]').val();

            // if (!area_id) {
            //     isValid = false;
            //     errorMessage += 'El campo "Area" es obligatorio.\n';
            // }
            // if (!category_id) { 
            //     isValid = false;
            //     errorMessage += 'El campo "Categoria" es obligatorio.\n';
            // }
            if (!coment) {
                isValid = false;
                errorMessage += 'El campo "Mensaje" es obligatorio.\n';
            }

            // if (!isValid) {
            //     alert(errorMessage);
            // }
            if (!isValid) {
            $('#errorContainer').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            } else {
                $('#errorContainer').empty(); // Limpiar cualquier mensaje de error previo
            }

            return isValid;
        }
        /** fin de la secccion de validacion */

        function sendMessage() {
            if (!validateForm()) {
            return; // Detener el envío si el formulario no es válido
            }
            var formData = new FormData($('#gestionform')[0]);
                    // Agregar las imágenes pegadas
                $('#imagePreviewContainer img').each(function(index, img) {
                    var blob = dataURLToBlob($(img).attr('src'));
                    formData.append('pastedImages[]', blob, 'pastedImage' + index + '.png');
                });
                $.ajax({
                    url: "{{ route('gestion.store') }}", // Reemplaza con tu endpoint de servidor
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        loadGestiones();
                        $('#messageInput').val('');
                        $('#fileInput').val('');
                        $('#imagePreviewContainer').empty();
                        
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending message:', error);
                        console.log(xhr.responseText);
                        alert(`Error: ${xhr.status} - ${xhr.responseText}`);
                        $('#errores').append(error);
                    }
                });        
        }

        function dataURLToBlob(dataURL) {
            var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type:mime});
        }

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

});
</script>