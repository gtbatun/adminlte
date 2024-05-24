@extends('adminlte::page')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
<!--  -->



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

<!-- ------------------------ -->

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


@endsection
