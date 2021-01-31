@extends("developer.template")

@section("title")
    Добавить текст страниц
@endsection

@section("h3")
    <h3>Добавить текст страниц</h3>
@endsection

@section("main")
    <form action="{{ route('lang-pages-add-save') }}" method="POST">
        @csrf
        <div>
            <label for="text">Текст</label>
            <input type="text" name="text" id="text">
        </div>
        <div>
            <label for="key">Ключ</label>
            <input type="text" name="key" id="key">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.add')" class="button">
        </div>
    </form>
@endsection
