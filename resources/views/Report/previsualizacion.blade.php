
@if($tickets->isEmpty())
    <p class="text-danger">No hay tickets para mostrar.</p>
@else

<a class="btn btn-outline-primary mt-4" href="{{ url('ticket-export1')}}" class="btn btn-success">Descargar <i class="fas fa-download" style="font-size:24px"></i></a>


<table class="table table-bordered shadow-lg mt-4 table-striped"><thead>
        <tr>
            <th>ID</th>
            <th>Estatus</th>            
            <th>Categoria</th>
            <th>Fecha</th>
            <!-- Otros campos de ticket -->
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->status->name }}</td>
                <td>{{ $ticket->category->name }}</td>
                <td>{{ $ticket->created_at }}</td>
                <!-- Otros campos de ticket -->
            </tr>
        @endforeach
    </tbody>
</table>

@endif