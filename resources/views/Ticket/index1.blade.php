@extends('adminlte::page')
@section('content')
<div class="row" >
    <div class="col-12 mt-4 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>
        <!-- <div class="d-flex justify-content-between"></div> -->
        <a class="btn btn-primary" href="{{route('ticket.index')}}"> Regresar a Tickets</a>

            
        @else
            <h3 >@lang('Tickets')</h3>
        @endisset
        <a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>        
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

    <!-- <h1>
    Tickets
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create-category">
        Crear
    </button>
    </h1> -->
    

    <!-- {{count($ticket)}} -->
    @if(count($ticket) != 0)  
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card fluid">
                <!-- <div class="card-header d-flex justify-content-between"> -->
                    <!-- <h3 class="card-title">Listado de Tickets</h3> -->
                <!-- </div> -->
            <!-- /.card-header -->
            <div class="table-responsive ">
                <table id="tickets"  class="table table-bordered shadow-lg mt-4 
                table-striped  ">
                    <thead  class="table-dark ">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>TICKET</th>
                            <!-- <th>CREACION</th> -->
                            <!-- <th>DEPARTAMENTO</th> -->
                            <th>CATEGORIA</th>
                            <th>AREA</th>
                            <th>ESTATUS</th>
                            <th>ACCION</th>
                        </tr>
                    </thead>
                    @foreach($ticket as $ticketItem) 
                    @can('view',$ticketItem)
                    <tr>
                <td>{{$ticketItem->id}}</td>   
                <td  >
                    <div >
                        
                    <!-- <a href="{{ route('ticket.show', $ticketItem) }}" title="{{$ticketItem->title}}">{{ $ticketItem->title }}</a> -->
                    <!-- <span class="text-secondary text-sm ">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span> -->
                    <!-- <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-secondary">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span> -->
                    </div>
                    <div class="d-flex align-items-center">
                     <!-- <p class="text-center  text-white rounded-circle shadow-lg  p-3">{{ $ticketItem->usuario->name }}</p>  -->
                     <!-- <img src="{{ asset('storage/images/user/'. $ticketItem->image) }}" alt="{{ $ticketItem->usuario->name }}"> -->
                     <!-- <img src="{{asset('storage/images/'. $ticketItem->images)}}" alt="{{ $ticketItem->usuario->name}}" class="img-thumbnail"> -->
                     <!-- asset('storage/images/user/' . $this->image);                     -->
                    <!-- <div class="ms-3">
                        <p class="fw-bold mb-1">{{ $ticketItem->title }}</p>
                        <p class="text-muted mb-0">{{ $ticketItem->created_at->diffForHumans(null, false, false, 1) }}</p>
                     </div> -->
                     <!-- <h1>A</h1> -->
                    </div>                     
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $ticketItem->usuario->name }}</h5>
                        <small class="text-success">{{ $ticketItem->created_at->diffForHumans(null, false, false, 1) }}</small>
                        </div> 
                        <div class="text-truncate">
                            <a href="{{ route('ticket.show', $ticketItem) }}" title="{{$ticketItem->title}}">
                        <p style="max-width: 300px; max-height: 15px;" >{{ $ticketItem->title }}</p>
                        </a>
                        </div>
                        
                    </div>
                    
                </td>
                <!-- <td>{{$ticketItem->created_at->diffForHumans(null, false, false, 1)}}</td>              -->
                <!-- <td>{{$ticketItem->department->name}}</td> -->
                <td>{{$ticketItem->category->name}}</td>
                <td>{{$ticketItem->area->name}}</td>
                <td>
                {{$ticketItem->status->name}}
                    <!-- <a href="{{route('status.show',$ticketItem->status)}}"></a> -->
                </td>                
                <td class="justify-content-between">
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{route('ticket.show',$ticketItem)}}" title="Gestionar" class="btn btn-success mr-2"> Ver <i class='fas fa-eye'></i></a>                    
                        @can('admin-access')
                        <a href="{{route('ticket.edit',$ticketItem)}}" class="btn btn-warning mr-2">Editar <i class='fas fa-edit'></i></a>               
                        <form action="{{route('ticket.destroy',$ticketItem)}}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-3 ">Eliminar <i class='fas fa-eraser'></i></button>
                        </form>
                        @endcan
                    </div>
                </td>                
            </tr>          
            @endcan
                    @endforeach
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
<!-- {{ $ticket->links() }} -->
    @else
    <div class="container" >
    <h3 class="text-center mt-5">Sin tickets creados, seleccioné el boton crear nuevo ticket</h3>
    </div>
    @endisset
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

@endsection

@section('js')
<script>
    
$(document).ready(function() {
    $('#tickets').DataTable({
        "order": [[ 0,"desc" ]],
        "language": {
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ ticket por pagina",
                "info":"Mostrando _START_ de _END_ de _TOTAL_ ",
                "infoFiltered":   "( filtrado de un total de _MAX_)",
                "emptyTable":     "Sin Datos a Mostrar",
                "zeroRecords":    "No se encontraron coincidencias",
                "infoEmpty":      "Mostrando 0 de 0 de 0 coincidencias",
                "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Ultimo",
                        },
            }
    } );
} );
</script>

@stop
