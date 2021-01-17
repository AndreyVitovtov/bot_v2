@extends("developer.template")

@section("title", "Привилегии")

@section("h3")
    <h3>Привилегии</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/permissions.css')}}">
    <table>
        <tr>
            <td>
                Имя привилегии
            </td>
            <td>
                Удалить
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
            <label for="">Имя привилегии:</label>
            <input type="text" name="permission">
        </div>
        <br>
        <div>
            <input type="submit" value="Добавить" class="button">
        </div>
    </form>
@endsection
