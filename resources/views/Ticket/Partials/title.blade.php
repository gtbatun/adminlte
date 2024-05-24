 <div style="max-height: 15px" class="pb-5">
    <div class="d-flex w-100 justify-content-between">
        <h5>{{ $ticket->usuario->name }}</h5>
        <small class="text-success">{{ $ticket->updated_at->diffForHumans() }}</small>
    </div>
    <div class="text-truncate">
        <a href="{{ route('ticket.show', $ticket) }}" title="{{ $ticket->title }}">
            <p  style="max-width: 300px; max-height: 15px;">{{ $ticket->title }}</p>
        </a>
    </div>
</div>
