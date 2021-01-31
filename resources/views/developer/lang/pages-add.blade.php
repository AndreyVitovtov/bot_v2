@extends("developer.template")

@section("title")
    @lang('pages.add_text_pages')
@endsection

@section("h3")
    <h3>@lang('pages.add_text_pages')</h3>
@endsection

@section("main")
    <form action="{{ route('lang-pages-add-save') }}" method="POST">
        @csrf
        <div>
            <label for="text">@lang('pages.text')</label>
            <input type="text" name="text" id="text">
        </div>
        <div>
            <label for="key">@lang('pages.key')</label>
            <input type="text" name="key" id="key">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.add')" class="button">
        </div>
    </form>
@endsection
