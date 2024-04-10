@extends('adminlte::page')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row">

<div class="col-md-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">                
                <div class="card-body">
                <h3 class="text-center">Tickets por agente</h3>
                    <canvas id="agente" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <h3 class="text-center">Tickets por Departamento</h3>
                    <canvas id="departamento" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="row">
        <div class="col-md-12">
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
        type: 'bar',
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
@endsection
