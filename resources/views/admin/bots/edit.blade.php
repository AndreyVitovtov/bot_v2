@extends("admin.template")

@section("title")
    @lang('pages.edit_bot')
@endsection

@section("h3")
    <h3>@lang('pages.edit_bot')</h3>
@endsection

@section("main")
    <div class="overflow-X-auto">
        <form action="{{ route('bots-edit-save') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $bot->id }}">
            <label for="messenger">@lang('pages.messenger')</label>
            <select name="messenger" id="messenger">
                @foreach($messengers as $messenger)
                    <option value="{{ $messenger->id }}"
                    @if($bot->messenger_id == $messenger->id)
                        selected
                    @endif
                    >{{ $messenger->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="language">@lang('pages.language')</label>
            <select name="language" id="language">
                @foreach($languages as $language)
                    <option value="{{ $language->id }}"
                    @if($bot->languages_id == $language->id)
                        selected
                    @endif
                    >{{ $language->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="name">@lang('pages.name')</label>
            <input type="text" name="name" id="name" value="{{ $bot->name }}">
            <br>
            <label for="token">@lang('pages.token')</label>
            <input type="text" name="token" id="token" value="{{ $bot->token }}">
            <br>
            <br>
            <input type="submit" value="@lang('pages.save')" class="button">
        </form>
    </div>
@endsection
