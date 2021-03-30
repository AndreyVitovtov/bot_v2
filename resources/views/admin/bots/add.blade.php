@extends("admin.template")

@section("title")
    @lang('pages.add_bot')
@endsection

@section("h3")
    <h3>@lang('pages.add_bot')</h3>
@endsection

@section("main")
    <div class="overflow-X-auto">
        <form action="{{ route('bots-add-save') }}" method="POST">
            @csrf
            <label for="messenger">@lang('pages.messenger')</label>
            <select name="messenger" id="messenger">
                @foreach($messengers as $messenger)
                    <option value="{{ $messenger->id }}">{{ $messenger->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="language">@lang('pages.language')</label>
            <select name="language" id="language">
                @foreach($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="name">@lang('pages.name')</label>
            <input type="text" name="name" id="name">
            <br>
            <label for="token">@lang('pages.token')</label>
            <input type="text" name="token" id="token">
            <br>
            <br>
            <input type="submit" value="@lang('pages.add')" class="button">
        </form>
    </div>
@endsection
