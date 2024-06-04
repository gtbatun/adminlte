@extends('adminlte::page')
@section('content')

<script src="{{asset('assets/js/plugins/chart-4.4.3.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script>


<!-- CSS Files -->
<!-- <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" /> -->

<!--  -->
<!-- <div class="container"> -->
    <!-- <div class="row"> -->
  @if($ticketCounts)
  <span>Resumen</span>
  <!-- {{$ticketCounts}} -->
  <div class="row mt-2">
  @foreach($ticketCounts as $ticketstatus)
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="mt-n4 position-absolute">
          <!-- <i class="bg-teal fas fa-lg fa-thumbs-up"></i> -->
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize"><strong>Ticket {{$ticketstatus->name}}</strong></p>
          <!-- <h4 class="mb-0">$53k</h4> -->
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3">
        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">{{$ticketstatus->total}} </span></p>
      </div>
    </div>
  </div>
  @endforeach
  @endif
  </div>
    <div class="row mt-2">
    
    </div>
    <!-- </div> -->

    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="row">
                <div class="col-md-11">
                    <div class="card">                
                        <div class="card-body">
                            <h3 class="text-center">Tickets por agente</h3>
                            <canvas id="agente" width="200" height="200"></canvas>
                            <label for="interval">Intervalo:</label>
                            <select id="interval">
                                <option value="day">Día</option>
                                <option value="week">Semana</option>
                                <option value="month">Mes</option>
                                <option value="year">Año</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12">
            <div class="row">
                <div class="col-md-11">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center">Tickets por Departamento</h3>
                            <canvas id="departamento" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12">
            <div class="row">
                <div class="col-md-11">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center">Tickets por Dia</h3>
                            <canvas id="dia" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
<!-- nueva grafica -->
<form id="reportForm" method="GET" action="{{ route('report.getData') }}">
    @csrf
    <div class="container d-flex justify-content-between">
        <div class="col-4 mb-1">
            <label for="start_date">Fecha Inicio:</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>
        <div class="col-4 mb-1">
            <label for="end_date">Fecha Fin:</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
        <div class="col-4 mb-1">
            <button class="btn btn-outline-primary mt-3" type="submit">Buscar</button>
        </div>
    </div>
</form>
<div class="col-md-4 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                <canvas id="nuevagrafica" width="400" height="400"></canvas>
                <button id="loadMore">Cargar más datos</button>
                <label for="interval">Intervalo:</label>
                <select id="interval">
                    <option value="day">Día</option>
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="year">Año</option>
                </select>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ----------------------------------------------------------------------------------------------------------------------- -->

 
<div class="col-md-4 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                    <canvas id="nuevagrafica2" width="400" height="400"></canvas>
                    <div class="col-4 mb-1">
                        <label for="interval">Intervalo:</label>
                        <select id="interval" name="interval">
                            <option value="day">Día</option>
                            <option value="week">Semana</option>
                            <option value="month">Mes</option>
                            <option value="year">Año</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------- -->


<script>
    var ctx = document.getElementById('agente').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data:{
            labels: {!! json_encode($a_labels) !!},
            datasets: [{
                label: 'Tickets por Agente',
                data: {!! json_encode($a_data) !!},
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(255, 192, 203)',
            ],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<!--  -->

<script>
    var ctx = document.getElementById('departamento').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data:{
            labels: {!! json_encode($d_labels) !!},
            datasets: [{
                label: 'Tickets por Departamento',
                data: {!! json_encode($d_data) !!},
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<!--  -->
<script>
    var ctx = document.getElementById('dia').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data:{
            labels: {!! json_encode($labels1) !!},
            datasets: [{
                label: 'Tickets por dia',
                data: {!! json_encode($data1) !!},
                backgroundColor: [
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor(),
                    getRandomColor()
                ],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
<script>
    $(document).ready(function() {
        var ctx = document.getElementById('nuevagrafica').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tickets por Agente',
                    data: [],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 192, 203)',
                    ],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateChart(data) {
            myChart.data.labels = [];
            myChart.data.datasets[0].data = [];
            data.forEach(function(item) {
                myChart.data.labels.push(item.user_id); // Aquí deberías traducir user_id al nombre de usuario si es posible
                myChart.data.datasets[0].data.push(item.ticket_count);
            });
            myChart.update();
        }

        $('#reportForm').on('submit', function(e) {
            e.preventDefault();

            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.data); // Verifica que los datos están llegando correctamente
                    updateChart(response.data);
                },
                error: function(xhr) {
                    console.error('Error fetching data:', xhr);
                    alert('Error fetching data. Please check the logs for more details.');
                }
            });
        });
    });
</script>

<script>
 document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('reportForm2');
    const intervalSelect = document.getElementById('interval');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchGraphData();
    });

    intervalSelect.addEventListener('change', fetchGraphData);

    function fetchGraphData() {
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();
        fetch(`{{ route('report.getData') }}?${queryString}`)
            .then(response => response.json())
            .then(data => {
                updateGraph(data);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function updateGraph(data) {
        // Aquí puedes usar la librería de gráficos que estés usando, por ejemplo Chart.js
        const ctx = document.getElementById('nuevagrafica2').getContext('2d');
        // Aquí debes crear o actualizar tu gráfico con los nuevos datos
        const chart = new Chart(ctx, {
            type: 'line', // o el tipo de gráfico que estés utilizando
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Datos',
                    data: data.values
                }]
            },
            options: {
                // Opciones de tu gráfico
            }
        });
    }
});   
</script>
@endsection
