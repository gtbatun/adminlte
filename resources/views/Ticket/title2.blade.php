<div style="max-height: 15px" class="pb-5">
    <div class="d-flex w-100 justify-content-between">
        <h5>{{ $ticket->usuario->name }}</h5>
        <small class="text-success">{{ $ticket->updated_at->diffForHumans() }}</small>
        <span class="direct-chat-name float-left">{{ $ticket->created_at}}</span>
        
    </div>
    <div class="text-truncate">
        <a href="{{ route('ticket.show', $ticket) }}" title="{{ $ticket->title }}">
            <p  style="max-width: 300px; max-height: 15px;">{{ $ticket->title }}</p>
        </a>
    </div>
</div>
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
