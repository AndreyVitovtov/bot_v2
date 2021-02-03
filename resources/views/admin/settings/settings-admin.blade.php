@extends("admin.template")

@section("title")
    @lang('pages.settings_admin')
@endsection

@section("h3")
    <h3>@lang('pages.settings_admin')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/settings.css')}}">
    <div class="settings">
        <form action="/admin/settings/save" method="POST">
            @csrf
            <div>
                <label for="name">@lang('pages.name')</label>
                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}">
            </div>
            <div>
                <label for="login">@lang('pages.login')</label>
                <input type="text" name="login" id="login" value="{{ Auth::user()->login }}">
            </div>
            <div>
                <label for="password">@lang('pages.new_password')</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="confirm_password">@lang('pages.confirm_password')</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>
            <div>
                <label for="name_bot">@lang('pages.name_bot')</label>
                <input type="text" name="name_bot" value="@lang('pages.bot_name')" id="name_bot">
            </div>
            <br>
            <div>
                <button class="button">@lang('pages.save')</button>
            </div>
        </form>
    </div>
@endsection
