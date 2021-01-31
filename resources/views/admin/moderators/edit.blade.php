@extends("admin.template")

@section("title")
    @lang('pages.moderators_edit')
@endsection

@section("h3")
    <h3>@lang('pages.moderators_edit')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/moderators.css')}}">

    <form action="{{ route('moderators-save-edit') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $moderator->id }}">
        <div>
            <label for="login">@lang('pages.login'):</label>
            <input type="text" name="login" value="{{ $moderator->login }}" id="login">
        </div>
        <div>
            <label for="password">@lang('pages.password'):</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="name">@lang('pages.name'):</label>
            <input type="text" name="name" value="{{ $moderator->name }}" id="name">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>
@endsection


