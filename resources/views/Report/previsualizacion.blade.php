
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
        /* Estilos opcionales para la tabla */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
    
@if($tickets->isEmpty())
    <p class="text-danger">No hay tickets para mostrar.</p>
@else
<div class="d-grid gap-2 d-md-flex justify-content-md-end m-2">
<a  href="{{ url('report-export', ['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]) }}" 
class="btn btn-success">Descargar<i class="fas fa-download" style="font-size:24px"></i></a>
</div>

<div class="table-responsive">
<table id="miTabla" class="table table-bordered shadow-lg mt-4 table-striped"><thead>
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
</div>
@endif
