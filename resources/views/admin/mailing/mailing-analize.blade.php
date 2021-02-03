@extends("admin.template")

@section("title")
    @lang('pages.analize')
@endsection

@section("h3")
    <h3>@lang('pages.analize')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/mailing.css')}}">
    <script src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>
    <script src="{{asset('js/charts/Chart.js')}}"></script>
    <script>
        let data = {!! json_encode($data) !!};
        chart.options.colors = ['#3c8dbc', '#FF0000'];
        chart.options.title = '@lang('pages.mailing_messages_sent')'+data.all
        chart.data = [
            ['', '@lang('pages.mailing_successfully')', '@lang('pages.mailing_not_successful')'],
            ['@lang('pages.mailing_count_messages')', data.true, data.false]
        ];
        chart.drawBar('chart_div');
    </script>

    <div class="chart_analize_mailing_log">
        <div id="chart_div"></div>
    </div>
    <br>
    <hr>
    <br>
    <div>
        <form action="/admin/mailing/mark-inactive-users" method="POST">
            @csrf
            <input type="submit" value="@lang('pages.analize_mark_inactive_users')" class="button">
        </form>
    </div>
@endsection
