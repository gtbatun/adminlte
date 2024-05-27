
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
    <div class="direct-chat-infos clearfix">
    <a href="{{ route('ticket.show', $ticket) }}" title="{{ $ticket->description }}">
        <span class="text-success float-left" >{{ $ticket->title }}</span>
    </a>
    <span class="{{ $messageClass }} float-right">{{$messageStatus}}</span>   
    </div>
    <div class="direct-chat-infos">    
        <span class="direct-chat-name float-left">{{$ticket->usuario->name}}</span>
        <span class="float-right">{{$gestionTime->diffForHumans()}}</span>
    </div>
</div>
