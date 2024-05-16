
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@extends('adminlte::page')
@section('content')
<div class="row" >
    <div class="col-12 mt-4 d-flex justify-content-between ">
        @isset($status)			    
            <h3 class="card-title">Tickets {{$status->name}}</h3>
        <a class="btn btn-primary" href="{{route('ticket.index')}}"> Regresar a Tickets</a>

            
        @else
            <h3 >@lang('Tickets')</h3>
        @endisset
        <a class="btn btn-primary" href="{{ route('ticket.create') }}">Crear Ticket <i class='far fa-file'></i></a>        
    </div>
    @if(Session::get('success'))
        <div class="alert alert-success mt-2">
        <strong>{{Session::get('success')}} </strong><br>
        </div>
    @endif
    @include('partials.validation-errors')

   
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div id="tickets-table" class="card fluid">
            </div>
        </div>  
    </div>
    </div>





@endsection



<script>
    function loadTickets() {
        $.ajax({
            url: "{{ route('tickets.data') }}",
            method: 'GET',
            success: function(data) {
                let ticketsHtml = '<table class="table table-bordered">';
                ticketsHtml += '<thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Created At</th></tr></thead><tbody>';
                
                data.forEach(ticket => {
                    ticketsHtml += `<tr>
                        <td>${ticket.id}</td>
                        <td>${ticket.title}</td>
                        <td>${ticket.description}</td>
                        <td>${ticket.created_at}</td>
                    </tr>`;
                });

                ticketsHtml += '</tbody></table>';
                $('#tickets-table').html(ticketsHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error loading tickets:', error);
            }
        });
    }

    $(document).ready(function() {
        loadTickets(); // Carga inicial de los tickets
        setInterval(loadTickets, 5000); // Actualiza la tabla cada 5 segundos
    });
</script>

