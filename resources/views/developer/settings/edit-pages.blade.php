@extends("developer.template")

@section("title")
    @lang('pages.edit_setting_page')
@endsection

@section("h3")
    <h3>@lang('pages.edit_setting_page')</h3>
@endsection

@section("main")
    <form action="/developer/settings/pages/save" method="POST">
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
            <label for="description">@lang('pages.description')</label>
            <input type="text" name="description" value="{{ $description }}" id="description">
        </div>
        <div>
            <label for="description_us">@lang('pages.description_us')</label>
            <input type="text" name="description_us" value="{{ $description_us }}" id="description_us">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>
@endsection
