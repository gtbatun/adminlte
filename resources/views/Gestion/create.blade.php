@extends('adminlte::page')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

@section('content')
@can('view',$ticket)
<!--  -->

<!--  -->
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
                <h6><strong>Usuario solicitante:</strong></h6>
                <span>{{$ticket->usuario->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Departamento:</strong></h6>
                <span>{{$ticket->department->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Area:</strong></h6>
                <span>{{$ticket->area->name}}</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 mt-2">
            <div class="form-group">
                <h6><strong>Categoria:</strong></h6>
                <span>{{$ticket->category->name}}</span>
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

<!--  -->

<!-- seccion para ver el historial de gestiones -->



<!--  -->

<!--  -->
@if(!empty($h_gestiones))
<div class="container bg-white shadow rounded" style="padding: 1%; border: 1px solid #adb5bd47;">
<h4>Historial  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">{{'+ '.count($h_gestiones)}}</span></h4>
    <div class="overflow-auto p-3" style="max-width: 100%; max-height: 300px;">
        @foreach ($h_gestiones as $index => $gestion)
        <div class="row rounded" style="padding: 1%; border: 1px solid #adb5bd47; {{$index % 2 == 0 ? 'background-color: #f8f9fa;' : ''}}">
            <div class="col-md-12 mt-2 rounded {{ $index % 2 == 0 ? 'ml-0' : 'ml-3' }}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $gestion->usuario->name }}</h5>
                    <small class="text-success">{{ $gestion->created_at }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <p>{{ $gestion->coment }}</p>
                </div>
                @if(!empty($gestion->image))
                <div class="form-group">
                    <strong>Adjunto</strong>
                    @foreach(explode(',', $gestion->image) as $imageItem)
                    <a href="{{asset('storage/images/'. $imageItem)}}" target="_blank" alt="{{$gestion->id }}">
                        <ul>
                            <li>{{ $imageItem }}</li>
                        </ul>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

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
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                <div class="form-group">
                    <strong>Agregar comentario:</strong>
                    <textarea class="form-control" style="height:150px" name="coment" require></textarea>
                    </div>
            </div>
            
           
            <!--  -->
            <div class="col-xs-12 col-sm-12 col-md-3 mt-2">
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
                    <select name="category_id" id="category" class="form-control border-0 bg-light shadow-sm ">
                        <option value=""> Seleccionar al puto del patas</option>
                        @foreach($category as $id => $name)
                        <option value="{{ $id }}" {{ $ticket->category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            

            <!--  -->
            <div class="col-xs-12 col-sm-12 col-md-6 mt-2 d-flex justify-content-end">
                @if($ticket->status_id != 4)            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="cerrar" {{ $ticket->status_id == 4 ? 'checked' : '' }}>
                    <label class="form-check-label text-danger" for="status_id"><strong>Cerrar Ticket</strong></label>            
                </div>
                @endif
                @if($ticket->status_id == 4)            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2" name="reopen" >
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
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                <a  class="btn btn-primary" href="{{route('ticket.index')}}" >Cancelar</a>
                <button type="submit" id="submitGt" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
    </div>

    <script>
    document.getElementById('gestion').addEventListener('submit', function() {
        document.getElementById('submitGt').setAttribute('disabled', 'true');
    });
    </script>

@endcan    
@endsection