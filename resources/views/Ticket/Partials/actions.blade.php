<div class="d-flex justify-content-center  align-items-center">
    <a href="{{ route('ticket.show', $ticket) }}" title="Gestionar" class="btn btn-success mr-2"> Ver <i class='fas fa-eye'></i></a>
    @if($ticket->department_id == auth()->user()->department_id || in_array(auth()->user()->is_admin,['10','5']))
    <button type="button" class="btn btn-secondary modal-reasig-btn mr-2" data-toggle="modalreasig" data-target="#modal-reasig-ticket" data-ticket-id="{{ $ticket->id }}" data-ticket-title="{{ $ticket->title }}">Reasignar</button>
   @endif
    @can('admin-access')
    <a href="{{ route('ticket.edit', $ticket) }}" class="btn btn-warning mr-2">Editar <i class='fas fa-edit'></i></a>
    <form action="{{ route('ticket.destroy', $ticket) }}" method="post" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar <i class='fas fa-eraser'></i></button>
    </form>
    @endcan
</div>
