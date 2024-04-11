<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div>
        <canvas id="myChart"></canvas>
    </div>

    <div>
        <label for="fromDate">From:</label>
        <input type="date" id="fromDate">

        <label for="toDate">To:</label>
        <input type="date" id="toDate">

        <button onclick="applyFilters()">Apply</button>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tickets created',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
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

        function applyFilters() {
            var fromDate = document.getElementById('fromDate').value;
            var toDate = document.getElementById('toDate').value;

            $.ajax({
                url: '{{ route("tickets.data") }}',
                method: 'GET',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function(response) {
                    myChart.data.labels = response.labels;
                    myChart.data.datasets[0].data = response.data;
                    myChart.update();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>
