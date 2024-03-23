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
       <div class="col-16 mt-4">
        <table class="table table-bordered text-black">
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
                <!-- <td><img src="{{ $ticketItem->image }}" alt="{{ $ticketItem->id }}" class="img-thumbnail"/></td> -->
                <!-- <td>
                @if($ticketItem->image)
                <img class="card-img-top mb-2"
                style="height:150px; object-fit: cover;"
                src="/storage/{{ $ticketItem->image}}"
                alt="{{$ticketItem->title}}">
                @endif
                </td> -->

                <td>
                    <a href="{{route('ticket.show',$ticketItem)}}">{{$ticketItem->title}}</a>                    
                    <h6 class="text-secondary">{{$ticketItem->created_at->diffForHumans(null, false, false, 2)}}</h6>
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