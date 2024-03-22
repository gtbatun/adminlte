@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="col-12 mt-4">
       
        <div>
            <a href="ticket/create" class="btn btn-primary">Crear Ticket</a>
        </div>
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>

        @endif

    
    @isset($ticket)    
       <div class="col-16 mt-4">
        <table class="table table-bordered text-black">
            <tr class="text-secondary">
                <th>ID</th>
                <th>Imagen</th>
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
                <td>
                @if($ticketItem->image)
                <img class="card-img-top mb-2"
                style="height:150px; object-fit: cover;"
                src="/storage/{{ $ticketItem->image}}"
                alt="{{$ticketItem->title}}">
                @endif
                </td>

                <td class="fw-bold"><a href="{{route('ticket.show',$ticketItem)}}">{{$ticketItem->title}}</a></td>
                
                <td>{{$ticketItem->department->name}}</td>
                <td>{{$ticketItem->category->name}}</td>
                <td>{{$ticketItem->area->name}}</td>
                <td>{{$ticketItem->status->name}}</td>
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