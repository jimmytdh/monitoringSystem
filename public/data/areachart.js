var presets = window.chartColors;
var utils = Samples.utils;
var inputs = {
    min: -100,
    max: 100,
    count: 8,
    decimals: 2,
    continuity: 1
};

function generateData(config) {
    return utils.numbers(Chart.helpers.merge(inputs, config || {}));
}

function generateLabels(config) {
    return utils.months(Chart.helpers.merge({
        count: inputs.count,
        section: 3
    }, config || {}));
}

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

new Chart('areaChart', {
    type: 'line',
    data: {
        labels: generateLabels(),
        datasets: [{
            backgroundColor: utils.transparentize(presets.red),
            borderColor: presets.red,
            data: generateData(),
            label: 'Data',
            fill: false
        }]
    }
});
