@extends('adminlte::page')
@section('content')

<script src="{{asset('assets/js/plugins/chart-4.4.3.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery-3.7.1.min.js')}}"></script>

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
<div class="col-md-6 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                <h3 class="text-center">Tickets por agente/meses</h3>
                <select id="s_month" >
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Augosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
                </div>
                <canvas id="agentmonth" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row mt-2">
<!-- ----------------------------------------------------------------------------------------------------------------------- -->
<!-- Grafica de tickets por agente, con opcion de vista por dia, semana, mes -->
<div class="col-md-4 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                <h3 class="text-center">Tickets por agente</h3>
                <select id="timeRange">
                    <option value="day">Día</option>
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="year">Año</option>
                </select>
                <canvas id="chartdsm"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- grafica de ticket por departamento -->
<div class="col-md-4 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                <h3 class="text-center">Tickets por Departamento</h3>
                <select id="timeRangedep">
                    <option value="day">Día</option>
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="year">Año</option>
                </select>
                <canvas id="deptdsm"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- grafica de tickets por dia -->
<div class="col-md-4 col-xs-12">
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body">
                <h3 class="text-center">Tickets por Departamento</h3>
                <select id="timeRangedxm">
                    <option value="day">Día</option>
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="year">Año</option>
                </select>
                <canvas id="dxmtdsm"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script>
    /** ------------------------------------------------------------------------------- */
            document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('agentmonth').getContext('2d');
            var chart;
            function fetchData(month){
                fetch(`/chart-per-month?month=${month}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                });
            }
            function updateChart(data){
                if(chart){
                    chart.destroy();
                }           
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels, 
                            datasets: [{
                            label: 'Tickets',
                            data: data.data,
                            backgroundColor:[
                            'rgb(255, 99, 132)',
                            'rgb(205, 199, 32)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(255, 192, 203)'],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            // Obtener el mes actual
            const currentMonth = new Date().getMonth() + 1;
            // Seleccionar el mes actual en el select
            document.getElementById('s_month').value = currentMonth;
            // Cargar los datos del mes actual
            fetchData(currentMonth);

            document.getElementById('s_month').addEventListener('change', function() {
            fetchData(this.value);
            });
        });
    /**------------------------------------------------------------------------------------- */
    /**seccion de la grafica de tickets resuletos por agente de sistemas o por agente */
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('chartdsm').getContext('2d');
        var chart;
        function fetchData(range) {
            fetch(`/chart-data?range=${range}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                });
        }
        function updateChart(data) {
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tickets',
                        data: data.data,
                        backgroundColor:[
                            'rgb(255, 99, 132)',
                            'rgb(205, 199, 32)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(255, 192, 203)',],
                        borderColor: 'rgba(255, 255, 255, 1)',
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
        }
        document.getElementById('timeRange').addEventListener('change', function() {
            fetchData(this.value);
        });
        // Cargar datos iniciales
        fetchData('day');
    });

/** ------------------- seccion de tickets creados por departamento ---------------------------------------- */
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('deptdsm').getContext('2d');
        var chart;
        function fetchData(range) {
            fetch(`/chart-by-department?range=${range}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                });
        }
        function updateChart(data) {
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tickets',
                        data: data.data,
                        backgroundColor:[
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 192, 203)',],
                        borderColor: 'rgba(255, 255, 255, 1)',
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
        }
        document.getElementById('timeRangedep').addEventListener('change', function() {
            fetchData(this.value);
        });
        // Cargar datos iniciales
        fetchData('day');
    });
/** ------------------- seccion de tickets creados por dia en todo el mes presente ---------------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('dxmtdsm').getContext('2d');
        var chart;
        function fetchData(range) {
            fetch(`/chart-per-day?range=${range}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                });
        }
        function updateChart(data) {
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tickets',
                        data: data.data,
                        backgroundColor:[
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 192, 203)',],
                        borderColor: 'rgba(255, 255, 255, 1)',
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
        }
        document.getElementById('timeRangedxm').addEventListener('change', function() {
            fetchData(this.value);
        });
        // Cargar datos iniciales
        fetchData('day');
    });
</script>
@endsection
