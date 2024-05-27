
<style>
    .status-enviado {
    color: blue;
}

.status-respondido {
    color: green;
}

.status-pendiente {
    color: orange;
}

.status-sin-respuesta {
    color: red;
}

.status-ultima-gestion {
    color: purple;
}
</style>
<div class="direct-chat-msg" >    
    <div class="direct-chat-infos">
    <a class="d-inline-block text-truncate" style="max-width: 300px; font-size: 1.2em;" href="{{ route('ticket.show', $ticket) }}" title="{{ $ticket->description }}">        
         <span>{{ $ticket->title }}</span>
    </a>
    <span class="float-right">{{$gestionTime->diffForHumans()}}</span>
       
    </div>
    <div class="direct-chat-infos">    
        <span class="direct-chat-name float-left">{{$ticket->usuario->name}}</span>
        <span class="{{ $messageClass }} float-right">{{$messageStatus}}</span>
    </div>
</div>
