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

@endsection