@extends("developer.template")

@section("title")
    @lang('pages.settings_main')
@endsection

@section("h3")
    <h3>@lang('pages.settings_main')</h3>
@endsection

@section("main")
    <table>
        <tr>
            <td>№</td>
            <td>@lang('pages.field_name')</td>
            <td>@lang('pages.prefix')</td>
            <td>@lang('pages.value')</td>
            <td>@lang('pages.type')</td>
            <td>@lang('pages.actions')</td>
        </tr>
        @foreach($fields as $field)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $field['name'] }}</td>
                <td>{{ mb_strtoupper($field['prefix']) }}</td>
                <td>{{ mb_strimwidth($field['value'], 0, 100, "...") }}</td>
                <td>{{ $field['type'] }}</td>
                <td class="actions">
                    <div>
                        <form action="/developer/settings/main/edit" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-pen'></i>
                            </button>
                        </form>
                        <form action="/developer/settings/main/delete" method="POST">
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
    <form action="/developer/settings/main/add" method="POST">
        @csrf
        <div>
            <label for="name">@lang('pages.field_name'):</label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="name_us">@lang('pages.field_name_us'):</label>
            <input type="text" name="name_us" id="name_us">
        </div>
        <div>
            <label for="prexix">@lang('pages.prefix')</label>
            <input type="text" name="prefix" id="prefix">
        </div>
        <div>
            <label for="value">@lang('pages.value')</label>
            <input type="text" name="value" id="value">
        </div>
        <div>
            <label for="type">@lang('pages.type')</label>
            <select name="type" id="type">
                <option value="number">Number</option>
                <option value="text">Text</option>
                <option value="date">Date</option>
            </select>
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.add')" class="button">
        </div>
    </form>
@endsection
