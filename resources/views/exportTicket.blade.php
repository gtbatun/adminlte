<table>
    <thead>
        <tr>
            <th>ID</th>
            <!-- <th>Titulo</th>
            <th>Descripcion</th>
            <th>Dia</th>
            <th>Usuario</th>
            <th>Area</th>
            <th>Departamento</th> -->
        </tr>
        <tbody>
            @foreach($ticket as $ticketItem)
            <tr>
                <td>{{$ticketItem->id}}</td>
                <!-- <td>{{$ticketItem->title}}</td>
                <td>{{$ticketItem->description}}</td>
                <td>{{$ticketItem->created_at}}</td>
                <td>{{$ticketItem->user_id}}</td>
                <td>{{$ticketItem->area_id}}</td>
                <td>{{$ticketItem->department_id}}</td> -->
            </tr>
            @endforeach
        </tbody>
    </thead>
</table>