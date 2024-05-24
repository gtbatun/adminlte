@extends('adminlte::page')
@section('content')
<div class="row" >
    <div class="col-12 mt-1 d-flex justify-content-between ">
        <h3 >@lang('Tickets')</h3>
        <a class="btn btn-primary " href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>        
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-1">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

    
    @if(count($ticket) != 0)  
    <div class="col-12 mt-1">
        <!-- <div class="card fluid"> -->
            <div class="card fluid">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="tickets_clo"  class="table table-striped table-bordered dt-responsive nowrap" style="width:98%">
                            <thead >
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>TICKET</th>
                                    <th>CATEGORIA</th>
                                    <th>AREA</th>
                                    <th>ESTATUS</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($ticket as $ticketItem) 
                            @can('view',$ticketItem)
                            <tr>
                                <td>{{$ticketItem->id}}</td>   
                                <td >                    
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
                                <td>{{$ticketItem->category->name}}</td>
                                <td>{{$ticketItem->area->name}}</td>
                                <td>
                                {{$ticketItem->status->name}}
                                </td>                
                                <td class="justify-content-between">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{route('ticket.show',$ticketItem)}}" title="Gestionar" class="btn btn-success mr-2"> Ver <i class='fas fa-eye'></i></a>                    
                                        @can('admin-access')
                                        <a href="{{route('ticket.edit',$ticketItem)}}" class="btn btn-warning mr-2">Editar <i class='fas fa-edit'></i></a>               
                                        <form action="{{route('ticket.destroy',$ticketItem)}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger  ">Eliminar <i class='fas fa-eraser'></i></button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>                
                            </tr>          
                            @endcan
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>  
    @else
    <div class="container" >
    <h3 class="text-center mt-5">Sin tickets cerrados</h3>
    </div>
    @endisset
</div>


@endsection

@section('js')
<script>
    
$(document).ready(function() {
    $('#tickets_clo').DataTable({
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

@endsection
