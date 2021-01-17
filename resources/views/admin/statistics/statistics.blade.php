@extends("admin.template")

@section("title")
    @lang('pages.statistics')
@endsection

@section("h3")
    <h3>@lang('pages.statistics')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/statistics.css')}}">
    <script>
        let texts = {};
        texts.count_users_visits = "@lang('pages.statistics_count_users_visits')";
        texts.date = "@lang('pages.statistics_date')";
        texts.count = "@lang('pages.statistics_count')";
        texts.users_count = "@lang('pages.statistics_users_count')";
        texts.count_users_country = "@lang('pages.statistics_count_users')";
        texts.count_users_messengers = "@lang('pages.statistics_count_users_messengers')";
        texts.access_title = "@lang('pages.statistics_access')";

        let statistics = {};
        @foreach($statistics as $key => $value)
            statistics['{{ $key }}'] = {!! json_encode($value) !!}
        @endforeach

        google.load('visualization', '1.0', {'packages': ['corechart'] });
        google.setOnLoadCallback(function() {
            drawChart(statistics, texts);
        });
    </script>

    @foreach($statistics as $key => $value)
        <div class="chart_statistics_2">
            <div id="chart_{{ $key }}"></div>
        </div>
    @endforeach
@endsection
