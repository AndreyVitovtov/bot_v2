@extends("developer.template")

@section("title")
    @lang('pages.edit_menu_admin_panel')
@endsection

@section("h3")
    <h3>@lang('pages.edit_menu_admin_panel')</h3>
@endsection

@section("main")
    <style>
        .item-border {
            border: solid 1px #ddd;
            padding: 5px;
            background-color: #fbfbfb;
            transition: 0.1s;
            margin: 10px 0;
            cursor: n-resize;
            position: relative;
        }

        .item-border:hover {
            background-color: #dcdcdc;
            transition: 0.1s;
        }

        .close {
            right: 0;
            top: 2px;
            position: absolute;
        }

        .close i {
            color: #d66c5b;
            cursor: pointer;
        }
    </style>

    <form action="{{ route('menu-admin-edit-save') }}" method="POST">
        @csrf
        <input type="hidden" name="key" value="{{ $key }}">
        <input type="hidden" name="type" value="{{ $menu['type'] }}">
        @if($menu['type'] == 'item')
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" value="{{ $menu['name'] }}" id="name">
            </div>
            <div>
                <label for="icon">Icon</label>
                <input type="text" name="icon" value="{{ $menu['icon'] }}" id="icon">
            </div>
            <div>
                <label for="menu">Menu</label>
                <input type="text" name="menu" value="{{ $menu['menu'] }}" id="menu">
            </div>
            <div>
                <label for="route">Route</label>
                <input type="text" name="url" value="{{ $menu['url'] }}" id="route">
            </div>
            <br>
        @else
            <div>
                <label for="nameItem">Name item</label>
                <input type="text" name="nameItem" value="{{ $menu['nameItem'] }}" id="nameItem">
            </div>
            <div>
                <label for="icon">Icon</label>
                <input type="text" name="icon" value="{{ $menu['icon'] }}" id="icon">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" value="{{ $menu['name'] }}" id="name">
            </div>
            <br>
            <label for="items">Items</label>
            <br>
            <div class="sortable">
                @foreach($menu['items'] as $keyItem => $item)
                    <div class="item-border">
                        <div class="close"><i class="icon-cancel-4"></i></div>
                        <div>
                            <label for="itemName">Item name</label>
                            <input type="text" name="itemName[]" value="{{ $item['name'] }}" id="itemName">
                        </div>
                        <div>
                            <label for="itemMenu">Item menu</label>
                            <input type="text" name="itemMenu[]" value="{{ $item['menu'] }}" id="itemMenu">
                        </div>
                        <div>
                            <label for="itemRoute">Item route</label>
                            <input type="text" name="itemUrl[]" value="{{ $item['url'] }}" id="itemRoute">
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="menu-items"></div>
            <div class="button" id="add_item">Add item</div>
            <br>
            <br>
        @endif
        <div>
            <input type="submit" value="@lang('pages.save')" class="button">
        </div>
    </form>


    <script>
        $('body').on('change', 'input[name="type"]', function() {
            if($(this).val() === 'item') {
                $('#rolled').hide();
                $('#item').show();
                $('.m-item').attr('disabled', false);
                $('.m-rolled').attr('disabled', true);
            } else {
                $('#rolled').show();
                $('#item').hide();
                $('.m-item').attr('disabled', true);
                $('.m-rolled').attr('disabled', false);
            }
        });

        $('body').on('click', '#add_item', function() {
            let html = '<div class="item-border">'+
                '<div class="close"><i class="icon-cancel-4"></i></div>'+
                '<div>'+
                '<label for="item-name-rolled">Item name</label>'+
                '<input type="text" name="itemName[]" class="m-rolled">'+
                '</div>'+
                '<div>'+
                '<label for="item-menu-rolled">Item menu</label>'+
                '<input type="text" name="itemMenu[]" class="m-rolled">'+
                '</div>'+
                '<div>'+
                '<label for="item-route-rolled">Item route</label>'+
                '<input type="text" name="itemUrl[]" class="m-rolled">'+
                '</div>'+
                '</div>';

            $('#menu-items').append(html);
        });

        $('.sortable').sortable({ axis: 'y' });

        $('body').on('click', '.close i', function() {
            $(this).parent().parent().remove();
        });

    </script>
@endsection
