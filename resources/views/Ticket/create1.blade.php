@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="col-12 mt-4">
    @isset($status)
			<div>
				<h1 class="display-4 mb-0">Tickets {{$status->name}}</h1>
			 	<a href="{{route('ticket.index')}}"> Regresar a Tickets</a>
			</div>
	@else
			<h1 class="display-4 mb-0">@lang('Ticket')</h1>
	@endisset
    <a class="btn btn-primary"
			href="{{ route('ticket.create') }}"
			>Crear Ticket</a>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

    
    @isset($ticket)    
       <div class="table-responsive">
        <table class="table table-bordered table-striped text-black">
            <tr class="text-secondary">
                <th>ID</th>
                <!-- <th>Imagen</th> -->
                <th>Ticket</th>
                <th>Departamento</th>
                <th>categoria</th>
                <th>Area</th>
                <th>Estatus</th>
                <th>Acción</th>
            </tr>
            @foreach($ticket as $ticketItem)
            <tr>
                <td>{{$ticketItem->id}}</td>   
                <td >
                    <div >
                    <a href="{{ route('ticket.show', $ticketItem) }}" title="Gestionar">{{ $ticketItem->title }}</a>
                    <!-- <span class="text-secondary text-sm ">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span> -->
                    <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-secondary">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span>
                    </div>
                </td>             
                <td>{{$ticketItem->department->name}}</td>
                <td>{{$ticketItem->category->name}}</td>
                <td>{{$ticketItem->area->name}}</td>
                <td><a href="{{route('status.show',$ticketItem->status)}}">{{$ticketItem->status->name}}</a></td>
                
                <td>
                    <a href="{{route('ticket.edit',$ticketItem)}}" class="btn btn-warning">Editar</a>

                    <form action="{{route('ticket.destroy',$ticketItem)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
                
            </tr>
            
            @endforeach            
        </table>
        {{$ticket->links()}}
    </div>
    @else
    <p>No hay tickets creados</p>
   @endisset
</div>


<!-- ----------------------------------------------------------- -->



<h1>
    Categorías
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-category">
        Crear
    </button>
</h1>



<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de categorías</h3>
                </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="categories" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                            <th>CSS grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trident</td>
                            <td>Internet
                            Explorer 4.0
                            </td>
                            <td>Win 95+</td>
                            <td> 4</td>
                            <td>X</td>
                        </tr>
                        <tr>
                            <td>Trident</td>
                            <td>Internet
                            Explorer 5.0
                            </td>
                            <td>Win 95+</td>
                            <td>5</td>
                            <td>C</td>
                        </tr>
                        <tr>
                            <td>Trident</td>
                            <td>Internet
                            Explorer 5.5
                            </td>
                            <td>Win 95+</td>
                            <td>5.5</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Trident</td>
                            <td>Internet
                            Explorer 6
                            </td>
                            <td>Win 98+</td>
                            <td>6</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Trident</td>
                            <td>Internet Explorer 7</td>
                            <td>Win XP SP2+</td>
                            <td>7</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Trident</td>
                            <td>AOL browser (AOL desktop)</td>
                            <td>Win XP</td>
                            <td>6</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Gecko</td>
                            <td>Firefox 1.0</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.7</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Gecko</td>
                            <td>Firefox 1.5</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Gecko</td>
                            <td>Firefox 2.0</td>
                            <td>Win 98+ / OSX.2+</td>
                            <td>1.8</td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>Gecko</td>
                            <td>Firefox 3.0</td>
                            <td>Win 2k+ / OSX.3+</td>
                            <td>1.9</td>
                            <td>A</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                            <th>CSS grade</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

<!-- modal -->
<div class="modal fade" id="modal-create-category">
    <div class="modal-dialog">
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h4 class="modal-title">Crear Categoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
            <div class="modal-body">
                <p>Proximamente, Formulario....</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
$(document).ready(function() {
    $('#categories').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
} );
</script>


@endsection