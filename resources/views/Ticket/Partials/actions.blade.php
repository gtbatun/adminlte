<div class="d-flex justify-content-center  align-items-center">
    <a href="{{ route('ticket.show', $ticket) }}" title="Gestionar" class="btn btn-success mr-2"> Ver <i class='fas fa-eye'></i></a>
    @can('admin-access')
    <a href="{{ route('ticket.edit', $ticket) }}" class="btn btn-warning mr-2">Editar <i class='fas fa-edit'></i></a>
    <form action="{{ route('ticket.destroy', $ticket) }}" method="post" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3">Eliminar <i class='fas fa-eraser'></i></button>
    </form>
    @endcan
</div>
