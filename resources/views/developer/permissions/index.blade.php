@extends("developer.template")

@section("title")
    @lang('pages.permissions')
@endsection

@section("h3")
    <h3>@lang('pages.permissions')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/permissions.css')}}">
    <table>
        <tr>
            <td>
                @lang('pages.permission_name')
            </td>
            <td>
                @lang('pages.delete')
            </td>
        </tr>
    @foreach($permissions as $permission)
        <tr>
            <td class="tal">
                {{ $permission->name }}
            </td>
            <td class="actions tac">
                <form action="{{ route('permission-delete') }}" method="POST" id="delete_permission_{{ $permission->id }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $permission->id }}">
                </form>
                <button form="delete_permission_{{ $permission->id }}"><i class="icon-trash-empty"></i></button>
            </td>
        </tr>
    @endforeach
    </table>
    <br>
    <form action="{{ route('permission-add') }}" method="POST">
        @csrf
        <div>
            <label for="">@lang('pages.permission_name'):</label>
            <input type="text" name="permission">
        </div>
        <br>
        <div>
            <input type="submit" value="@lang('pages.add')" class="button">
        </div>
    </form>
@endsection
