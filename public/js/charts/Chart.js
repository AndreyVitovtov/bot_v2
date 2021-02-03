class Chart {
    constructor() {
        this.options = {
            title: 'Title',
            width: '100%',
            height: 300,
            colors: ['#0088cc', '#665CAC']
        };

        this.data = [];
    }

    drawBar(elementId) {
        if(this.data.length === 0) {
            alert('Add data chart.data = ...');
            return;
        }
        let options = this.getOptions();
        let data = this.getData();
        google.load('visualization', '1.0', {'packages': ['corechart'] });
        google.setOnLoadCallback(function() {
            let chart = new google.visualization.BarChart(document.getElementById(elementId));
            chart.draw(google.visualization.arrayToDataTable(data), options);
        });
    }

    drawPie(elementId) {
        if(this.data.length === 0) {
            alert('Add data chart.data = ...');
            return;
        }
        let options = this.getOptions();
        delete(options.colors);
        let data = this.getData();
        google.load('visualization', '1.0', {'packages': ['corechart'] });
        google.setOnLoadCallback(function() {
            let chart = new google.visualization.PieChart(document.getElementById(elementId));
            chart.draw(google.visualization.arrayToDataTable(data), options);
        });
    }

    drawColumn(elementId) {
        if(this.data.length === 0) {
            alert('Add data chart.data = ...');
            return;
        }
        let options = this.getOptions();
        let data = this.getData();
        google.load('visualization', '1.0', {'packages': ['corechart'] });
        google.setOnLoadCallback(function() {
            let chart = new google.visualization.ColumnChart(document.getElementById(elementId));
            chart.draw(google.visualization.arrayToDataTable(data), options);
        });
    }

    getOptions() {
        let options = {};
        for(let key in this.options) {
            options[key] = this.options[key];
        }
        return options;
    }

    getData() {
        return this.data;
    }
}

let chart = new Chart;



// chart.data = [
//     ['', 'Test1', 'Test2'],
//     ['count', 100, 150]
// ];
// chart.drawBar('chartBar');
// chart.drawPie('chartPie');
// chart.drawColumn('chartColumn');
