@extends("developer.template")

@section("title")
    @lang('pages.edit_text_pages')
@endsection

@section("h3")
    <h3>@lang('pages.edit_text_pages')</h3>
@endsection

@section("main")
    <form action="{{ route('lang-pages-edit-save') }}" method="POST">
        @csrf
        <input type="hidden" name="key" value="{{ $key }}">
        <div>
            <label for="text_ru">@lang('pages.text') Ru</label>
            <input type="text" name="textRu" value="{{ $textRu }}" id="text_ru">
        </div>
        <div>
            <label for="text_us">@lang('pages.text') Us</label>
            <input type="text" name="textUs" value="{{ $textUs }}" id="text_us">
        </div>
        <div>
            <label for="key">@lang('pages.key')</label>
            <input type="text" name="newKey" value="{{ $key }}" id="key">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>
@endsection
