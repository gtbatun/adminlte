@extends('adminlte::page')
@section('content')
<div >
    <div class="col-12 mt-4 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>
        <!-- <div class="d-flex justify-content-between"></div> -->
        <a class="btn btn-primary" href="{{route('ticket.index')}}"> Regresar a Tickets</a>

            
        @else
            <h3 >@lang('Tickets')</h3>
        @endisset
        <a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket<i class='far fa-file'></i></a>        
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
    

    
    @isset($ticket)  
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card fluid">
                <!-- <div class="card-header d-flex justify-content-between"> -->
                    <!-- <h3 class="card-title">Listado de Tickets</h3> -->
                <!-- </div> -->
            <!-- /.card-header -->
            <div class="card-body ">
                <table id="categories" class="table table-bordered shadow-lg mt-4 
                table-striped  ">
                    <thead  class="table-dark ">
                        <tr>
                            <th>ID</th>
                            <th>TICKET</th>
                            <th>CREACION</th>
                            <!-- <th>DEPARTAMENTO</th> -->
                            <th>CATEGORIA</th>
                            <th>AREA</th>
                            <th>ESTATUS</th>
                            <th>ACCION</th>
                        </tr>
                    </thead>
                    @foreach($ticket as $ticketItem)                    
                    <tr>
                <td>{{$ticketItem->id}}</td>   
                <td class=" text-truncate" style="max-width: 200px;" >
                    <div >
                    <a href="{{ route('ticket.show', $ticketItem) }}" title="{{$ticketItem->title}}">{{ $ticketItem->title }}</a>
                    <!-- <span class="text-secondary text-sm ">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span> -->
                    <!-- <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-secondary">@lang($ticketItem->created_at->diffForHumans(null, false, false, 1))</span> -->
                    </div>
                </td>
                <td>{{$ticketItem->created_at->diffForHumans(null, false, false, 1)}}</td>             
                <!-- <td>{{$ticketItem->department->name}}</td> -->
                <td>{{$ticketItem->category->name}}</td>
                <td>{{$ticketItem->area->name}}</td>
                <td><a href="{{route('status.show',$ticketItem->status)}}">{{$ticketItem->status->name}}</a></td>
                
                <td>
                    <a href="{{route('ticket.edit',$ticketItem)}}" class="btn btn-warning">Editar <i class='fas fa-edit'></i></a>

                    <form action="{{route('ticket.destroy',$ticketItem)}}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar <i class='fas fa-eraser'></i></button>
                    </form>
                </td>
                
            </tr>          
                   
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
  
       
    @else
    <p>No hay tickets creados</p>
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
    $('#categories').DataTable( {
        "order": [[ 3, "desc" ]],
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_ ticket por pagina",
            "info":"Mostrando pagina _PAGE_ de _PAGES_ ",
            "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "first": "Primero",
                    "last": "Ultimo",
                    }
        }
    } );
} );
</script>
@stop