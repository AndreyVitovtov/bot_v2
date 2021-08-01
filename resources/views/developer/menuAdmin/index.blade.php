@extends("developer.template")

@section("title")
    @lang('pages.menu_admin_panel')
@endsection

@section("h3")
    <h3>@lang('pages.menu_admin_panel')</h3>
@endsection

@section("main")
    <style>
        .item-border {
            border: solid 1px #ddd;
            padding: 5px;
            background-color: #fbfbfb;
            transition: 0.1s;
            margin: 10px 0;
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

    <form action="{{ route('menu-admin-save') }}" method="POST">
        @csrf
        <input type="radio" name="type" value="item" id="type-item" checked>
        <label for="type-item" class="cursor-pointer"> - Item</label>
        <input type="radio" name="type" value="rolled" id="type-rolled">
        <label for="type-rolled" class="cursor-pointer"> - Rolled</label>
        <br>
        <div id="item">
            <label for="name-item">Name</label>
            <input type="text" name="name" class="m-item" id="name-item">
            <br>
            <label for="icon-item">Icon</label>
            <input type="text" name="icon" class="m-item" id="icon-item">
            <br>
            <label for="menu-item">Menu</label>
            <input type="text" name="menu" class="m-item" id="menu-item">
            <br>
            <label for="url-item">Route</label>
            <input type="text" name="url" class="m-item" id="url-item">
        </div>
        <div id="rolled" class="hidden">
            <div>
                <label for="name-rolled">Name item</label>
                <input type="text" name="nameItem" class="m-rolled" id="name-rolled" disabled>
            </div>
            <div>
                <label for="icon-rolled">Icon</label>
                <input type="text" name="icon" class="m-rolled" id="icon-rolled" disabled>
            </div>
            <div>
                <label for="name-rolled">Name</label>
                <input type="text" name="name" class="m-rolled" id="name-rolled" disabled>
            </div>
            <br>
            <label for="items">Items</label>
            <br>
            <div class="item-border">
                <div class="close"><i class="icon-cancel-4"></i></div>
                <label for="item-name-rolled">Item name</label>
                <input type="text" name="item_name[]" class="m-rolled" disabled>
                <br>
                <label for="item-menu-rolled">Item menu</label>
                <input type="text" name="item_menu[]" class="m-rolled" disabled>
                <br>
                <label for="item-route-rolled">Item route</label>
                <input type="text" name="item_url[]" class="m-rolled" disabled>
            </div>
            <div id="menu-items"></div>
            <br>
            <div class="button" id="add_item">Add item</div>
        </div>
        <br>
        <label for="add_after">Add after</label>
        <select name="add_after" id="add_after">
            <option value="last">Last</option>
            @foreach($menu as $name)
                <option value="{{ $name }}">{{ ucfirst($name) }}</option>
            @endforeach
        </select>
        <br>
        <br>
        <input type="submit" value="@lang('pages.add')" class="button">
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
                '<input type="text" name="item_name[]" class="m-rolled">'+
                '</div>'+
                '<div>'+
                '<label for="item-menu-rolled">Item menu</label>'+
                '<input type="text" name="item_menu[]" class="m-rolled">'+
                '</div>'+
                '<div>'+
                '<label for="item-route-rolled">Item route</label>'+
                '<input type="text" name="item_url[]" class="m-rolled">'+
                '</div>'+
            '</div>';

            $('#menu-items').append(html);
        });

        $('body').on('click', '.close i', function() {
            $(this).parent().parent().remove();
        });
    </script>
@endsection
