@extends("admin.template")

@section("title")
    @lang('pages.moderators_add')
@endsection

@section("h3")
    <h3>@lang('pages.moderators_add')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/moderators.css')}}">

    <form action="{{ route('moderators-save-add') }}" method="POST">
        @csrf
        <div>
            <label for="login">@lang('pages.moderators-add-login'):</label>
            <input type="text" name="login" id="login">
        </div>
        <div>
            <label for="password">@lang('pages.moderators-add-password'):</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="name">@lang('pages.moderators-add-name'):</label>
            <input type="text" name="name" id="name">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.moderators-add-add')" class="button">
        </div>
    </form>
@endsection


