@extends("developer.template")

@section("title")
    @lang('pages.edit_settings_buttons')
@endsection

@section("h3")
    <h3>@lang('pages.edit_settings_buttons')</h3>
@endsection

@section("main")
    <form action="/developer/settings/buttons/save" method="POST">
        <input type="hidden" name="id" value="{{ $id }}">
        @csrf
        <div>
            <label for="name">@lang('pages.name'):</label>
            <input type="text" name="name" value="{{ $name }}" id="name">
        </div>
        <div>
            <label for="text">@lang('pages.text')</label>
            <input type="text" name="text" value="{{ $text }}" id="text">
        </div>
        <div>
            <label for="menu">@lang('pages.menu')</label>
            <input type="text" name="menu" value="{{ $menu }}" id="menu">
        </div>
        <div>
            <label for="menu_us">@lang('pages.menu_us')</label>
            <input type="text" name="menu_us" value="{{ $menu_us }}" id="menu_us">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>
@endsection
