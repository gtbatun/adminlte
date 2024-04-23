
@if($tickets->isEmpty())
    <p class="text-danger">No hay tickets para mostrar.</p>
@else
<div class="d-grid gap-2 d-md-flex justify-content-md-end m-2">
<a  href="{{ url('report-export', ['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]) }}" 
class="btn btn-success">Descargar<i class="fas fa-download" style="font-size:24px"></i></a>
</div>


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