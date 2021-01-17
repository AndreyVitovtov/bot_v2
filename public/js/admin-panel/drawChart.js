"use strict";

function drawChart(statistics, texts) {
//Visits
    let visits = new google.visualization.DataTable();
    visits.addColumn('string', 'Дата');
    visits.addColumn('number', texts.count);
    visits.addRows(statistics.visits);

    let optionsVisits = {
        'title':texts.count_users_visits,
        'width':'100%',
        'height':300,
        'colors':['#3c8dbc']
    };

    let chartVisits = new google.visualization.ColumnChart(document.getElementById('chart_visits'));
    chartVisits.draw(visits, optionsVisits);


//Countries
    let countries = new google.visualization.DataTable();
    countries.addColumn('string', 'Страна');
    countries.addColumn('number', 'Количество');
    countries.addRows(statistics.countries);

    let optionsCountries = {
        'title':texts.count_users_country,
        'width':'100%',
        'height':300
    };

    let chartCountries = new google.visualization.PieChart(document.getElementById('chart_countries'));
    chartCountries.draw(countries, optionsCountries);


//Messengers
    let messengers = google.visualization.arrayToDataTable([
        ['', 'Telegram', 'Viber'],
        [texts.users_count, statistics.messengers.Telegram, statistics.messengers.Viber]
    ]);

    let optionsMessengers = {
        'title':texts.count_users_messengers,
        'width':'100%',
        'height':300,
        'colors':['#0088cc', '#665CAC']
    };

    let chartMessengers = new google.visualization.BarChart(document.getElementById('chart_messengers'));
    chartMessengers.draw(messengers, optionsMessengers);


//Access
//     let access = google.visualization.arrayToDataTable([
//         ['', 'Без доступа', 'Платный', 'Бесплатный'],
//         ['Кол. пользователей', statistics.access.no, statistics.access.paid, statistics.access.free]
//     ]);
//
//     let optionsAccess = {
//         'title':'Доступ',
//         'width':'100%',
//         'height':300,
//         'colors':['#3c8dbc', '#fed134', '#1cac5b']
//     };
//
//     let chartAccess = new google.visualization.BarChart(document.getElementById('chart_access'));
//     chartAccess.draw(access, optionsAccess);
}

function drawChartAnalizeMailingLog(data) {
    let analizeMailing = google.visualization.arrayToDataTable([
        ['City', texts.mailing_successfully, texts.mailing_not_successful],
        [texts.mailing_count_messages, data.true, data.false]
    ]);

    let optionsAnalizeMailing = {'title':texts.mailing_messages_sent+data.all,
        'width':'100%',
        'height':300,
        'colors':['#3c8dbc', '#FF0000']
    };

    let chartAnalizeMailing = new google.visualization.BarChart(document.getElementById('chart_div'));
    chartAnalizeMailing.draw(analizeMailing, optionsAnalizeMailing);
}
