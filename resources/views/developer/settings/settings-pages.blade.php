@extends("developer.template")

@section("title")
    @lang('pages.settings_pages')
@endsection

@section("h3")
    <h3>@lang('pages.settings_pages')</h3>
@endsection

@section("main")
    <table>
        <tr>
            <td>№</td>
            <td>@lang('pages.name')</td>
            <td>@lang('pages.text')</td>
            <td>@lang('pages.description')</td>
            <td>@lang('pages.actions')</td>
        </tr>
    @foreach($fields as $id => $field)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $field['name'] }}</td>
                <td>{{ base64_decode($field['text']) }}</td>
                <td>{{ $field['description'] }}</td>
                <td class="actions">
                    <div>
                        <form action="/developer/settings/pages/edit" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-pen'></i>
                            </button>
                        </form>
                        <form action="/developer/settings/pages/delete" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-trash-empty'></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
    @endforeach
    </table>
    <br>
    <form action="/developer/settings/pages/add" method="POST">
        @csrf
        <div>
            <label for="name">@lang('pages.name')</label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="text">@lang('pages.text')</label>
            <input type="text" name="text" id="text">
        </div>
        <div>
            <label for="description">@lang('pages.description')</label>
            <input type="text" name="description" id="description">
        </div>
        <div>
            <label for="description_us">@lang('pages.description_us')</label>
            <input type="text" name="description_us" id="description_us">
        </div>
        <br>
        <div>
            <button class="button">@lang('pages.add')</button>
        </div>
    </form>
@endsection
