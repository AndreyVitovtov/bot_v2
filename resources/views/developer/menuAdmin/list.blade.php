@extends("developer.template")

@section("title")
    @lang('pages.menu_admin_panel')
@endsection

@section("h3")
    <h3>@lang('pages.menu_admin_panel')</h3>
@endsection

@section("main")
    <style>
        .sortable td:nth-child(1) {
            font-weight: normal;
        }

        .sortable tr td:nth-child(1) {
            cursor: n-resize;
        }

        table td {
            width: 100%;
        }
    </style>

    <table>
        <thead>
            <tr>
                <td>@lang('pages.menu')</td>
                <td>@lang('pages.actions')</td>
            </tr>
        </thead>
        <tbody class="sortable">
        @foreach($menus as $key => $menu)
            <tr id="{{ $key }}">
                <td style="text-align: left;">
                    {{ ucfirst($menu['name']) }}
                </td>
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
        </tbody>
    </table>

    <script>
        $('.sortable').sortable({
            axis: 'y'
        });

        $('.sortable').on('sortstop', function() {
            let ids = [];
            $( ".sortable > tr").each( function( index, element) {
                ids.push($(element).attr('id'));
            });
            $.ajax({
                method: 'POST',
                url: '{{ route('sort-save') }}',
                data: {
                    'ids' : ids
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
@endsection
