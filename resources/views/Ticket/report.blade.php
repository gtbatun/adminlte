<h1>Reporte de Tickets</h1>

<p>Fecha de inicio: {{ $startDate }}</p>
<p>Fecha de fin: {{ $endDate }}</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Asunto</th>
            <th>Descripción</th>
            <th>Fecha de Creación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->subject }}</td>
            <td>{{ $ticket->description }}</td>
            <td>{{ $ticket->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>