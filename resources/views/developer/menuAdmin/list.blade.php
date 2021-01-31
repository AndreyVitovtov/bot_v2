@extends("developer.template")

@section("title")
    @lang('pages.menu_admin_panel')
@endsection

@section("h3")
    <h3>@lang('pages.menu_admin_panel')</h3>
@endsection

@section("main")
    <table>
        <tr>
            <td>@lang('pages.menu')</td>
            <td>@lang('pages.actions')</td>
        </tr>
        @foreach($menus as $key => $menu)
            <tr>
                <td style="text-align: left;">{{ ucfirst($menu['name']) }}</td>
                <td class="actions">
                    <div>
                        <form action="{{ route('menu-admin-edit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                            <button>
                                <i class='icon-pen'></i>
                            </button>
                        </form>
                        <form action="{{ route('menu-admin-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                            <button>
                                <i class='icon-trash-empty'></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
