<script type="text/javascript">
	var presets = window.chartColors;
    var utils = Samples.utils;
    var inputs = {
        min: -100,
        max: 100,
        count: 8,
        decimals: 2,
        continuity: 1
    };

    var options = {
        maintainAspectRatio: false,
        spanGaps: false,
        elements: {
            line: {
                tension: 0.000001
            }
        },
        plugins: {
            filler: {
                propagate: false
            }
        },
        scales: {
            xAxes: [{
                ticks: {
                    autoSkip: false,
                    maxRotation: 0
                }
            }]
        }
    };
	$.get("{{ url('/home/chart') }}", function(data, status)
{        console.log(data);
        var area = data.area;
        new Chart('areaChart', {
            type: 'line',
            data: {
                labels: area.day,
                datasets: [{
                    backgroundColor: utils.transparentize(presets.red),
                    borderColor: presets.red,
                    data: area.count,
                    label: 'Patients',
                    fill: false
                }]
            }
        });

        var config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: data.donut,
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'Fever',
                    'Cough',
                    'Colds',
                    'Sore Throat',
                    'Diarrhea'
                ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: "Year {{ date('Y') }}"
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };

        var ctx = document.getElementById('donutChart').getContext('2d');
        window.myDoughnut = new Chart(ctx, config);

        setTimeout(function () {
            $("#loader-wrapper").css('visibility','hidden');
        },1500);
    });
</script>
