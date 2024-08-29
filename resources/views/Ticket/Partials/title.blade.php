
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
    <a class="d-inline-block text-truncate notification-btn" data-ticket-id="{{ $ticket->id }}" 
        data-ticket-title="{{ $ticket->title }}" data-ticket-description="{{ $ticket->description }}" 
        style="max-width: 300px; font-size: 1.2em;" href="{{ route('ticket.show', $ticket) }}" 
        title="{{ $ticket->description }}">        
        <span>{{ $ticket->title }}</span>
    </a>
    
    
        <!-- <div class="form-group">    -->
        @if($notifications > 0)           
        <span class="badge rounded-circle bg-danger  float-right"> {{$notifications}}</span>  
        <i style="color: green; font-size: 20px;" class="fas fa-comments float-right">
            <!-- <span class="badge rounded-circle bg-success position-relative "> {{$notifications}}</span> -->
        </i>
           <!-- <span type="button" class="btn btn-primary rounded-pill float-right"><i class="fas fa-comments"></i>
                <span class="badge bg-secondary ">{{$notifications}}</span>
        </span> -->
        @endif
        <!-- <span class="{{ $messageClass }} float-right">{{$messageStatus}}  <i class="fas fa-comments"></i>3</span> -->
        <!-- </div> -->
       
    </div>
    <div class="direct-chat-infos">    
        <span class="direct-chat-name float-left">{{$ticket->usuario->name}}</span>
        <span class="float-right">{{$gestionTime->diffForHumans()}}</span>        
    </div>
</div>


