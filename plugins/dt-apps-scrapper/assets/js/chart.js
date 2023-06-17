
$(document).ready(function () {
    var ctx = document.getElementById('my-chart').getContext('2d');
    var myChart;
    document.getElementById('chart-select').addEventListener('change', function() {
        var period = this.value;

        // Make an AJAX request to get the updated data based on selected period
        $.ajax({
            type: 'GET',
            url: appsData.ajax_url,
            data: {
                period: period // send the selected period to the server
            },
            dataType: 'json',
            success: function(data) {


                // Update the chart data
                myChart.data.labels =  data.labels.map(function(dateString) {
                    return moment(dateString).toDate();
                });
                myChart.data.datasets[0].data = data.app_counts;

                // Update chart options based on selected period
                myChart.options.scales.xAxes[0].time.unit = period == 'daily' ? 'hour' : period == 'weekly' ? 'day' : 'week';

                // Redraw the chart
                myChart.update();
            }
        });
    });
    $.ajax({
        type: 'GET',
        url: appsData.ajax_url,
        dataType: 'json',
        success: function(data) {
            // Parse the JSON response from your PHP script
            var chartData = data;

            // Check if data is available
            if (Object.keys(data).length > 0) {
                var  chartValues= data.app_counts;
                var chartLabels = data.labels.map(function(dateString) {
                    return moment(dateString).toDate();
                });
            }

            // Format the data for use in Chart.js
            if (Object.keys(data).length > 0 && chartValues && chartLabels) {
                // Access properties of data here
                 myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'App Count',
                            data: chartValues,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)'
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: 'hour'
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        }
    });
});






