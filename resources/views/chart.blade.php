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

<!-- ------------------------ -->
<div class="row d-flex  ">
    <div class="col-md-4 col-sm-6">
        <x-adminlte-small-box title="424" text="Views" icon="fas fa-eye text-dark"
        theme="teal" url="#" url-text="View details"/>
    </div>
    <div class="col-md-4 col-sm-6">
        <x-adminlte-small-box title="528" text="User Registrations" icon="fas fa-user-plus text-teal"
        theme="primary" url="#" url-text="View all users"/>
    </div>
    <div class="col-md-4 col-sm-6">
        <x-adminlte-small-box title="0" text="Reputation" icon="fas fa-medal text-dark"
        theme="danger" url="#" url-text="Reputation history" id="sbUpdatable"/>
    </div>
    </div>
@push('js')
<script>

    $(document).ready(function() {

        let sBox = new _AdminLTE_SmallBox('sbUpdatable');

        let updateBox = () =>
        {
            // Stop loading animation.
            sBox.toggleLoading();

            // Update data.
            let rep = Math.floor(1000 * Math.random());
            let idx = rep < 100 ? 0 : (rep > 500 ? 2 : 1);
            let text = 'Reputation - ' + ['Basic', 'Silver', 'Gold'][idx];
            let icon = 'fas fa-medal ' + ['text-primary', 'text-light', 'text-warning'][idx];
            let url = ['url1', 'url2', 'url3'][idx];

            let data = {text, title: rep, icon, url};
            sBox.update(data);
        };

        let startUpdateProcedure = () =>
        {
            // Simulate loading procedure.
            sBox.toggleLoading();

            // Wait and update the data.
            setTimeout(updateBox, 2000);
        };

        setInterval(startUpdateProcedure, 10000);
    })

</script>
@endpush
<!-- ------------------------------- -->
@endsection
