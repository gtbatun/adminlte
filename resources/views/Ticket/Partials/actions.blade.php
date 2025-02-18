<div class="d-flex justify-content-center  align-items-center">
    <!-- <a href="{{ route('ticket.show', $ticket) }}" title="Gestionar" class="btn btn-success modal-gestion-btn mr-2"> Ver <i class='fas fa-eye'></i></a> -->
    <button type="button" class="btn btn-success modal-gestion-btn mr-2"
    data-ticket-id="{{ $ticket->id }}" 
    data-ticket-title="{{ $ticket->title }}" 
    data-ticket-description="{{ $ticket->description }}"
    data-notifications="{{ $notifications}}"
    title="Ver" ><i class='far fa-comment'></i></button>

    @if ($ticket->status_id != '7' && $ticket->department_id = '1')
     
    <button type="button" class="btn btn-secondary modal-agendarcita mr-2" data-toggle="modalagendarcita" data-target="#modal-agendarcita" 
    data-ticket-id="{{ $ticket->id }}" data-ticket-title="{{ $ticket->title }}" data-ticket-department="{{ $ticket->department_id }}">Agendar cita</button>
    @endif

    @if($ticket->department_id == auth()->user()->department_id || in_array(auth()->user()->is_admin,['10','5']))  
    <!-- <button type="button" class="btn btn-secondary modal-reasig-btn mr-2" data-toggle="modalreasig" data-target="#modal-reasig-ticket" 
    data-ticket-id="{{ $ticket->id }}" data-ticket-title="{{ $ticket->title }}" data-ticket-department="{{ $ticket->department_id }}"><i class='fas fa-project-diagram'></i></button> -->
    @endif
    @can('admin-access')
    <!-- <a href="{{ route('ticket.edit', $ticket) }}" class="btn btn-warning mr-2" title="Editar"><i class='fas fa-edit'></i></a>
    <form action="{{ route('ticket.destroy', $ticket) }}" method="post" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" title="Eliminar"><i class='fas fa-eraser'></i></button>
    </form> -->
    @endcan
</div>
<!-- se elimino del botn de ver -->
<!-- data-ticket-department-id="{{ $ticket->department_id }}" -->