@extends("developer.template")

@section("title")
    Добавить меню бота
@endsection

@section("h3")
    <h3>Добавить меню бота</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <script src="{{asset('js/developer-panel/add-menu.js')}}"></script>
    <label for="menu-name">Имя меню</label>
    <input type="text" name="menu-name" id="menu-name">
    <br>
    <br>
    <div class="flex">
        <div class="menu-bot">
            <div>
                <div class="str">
                    <div class="buttons buttons1"></div>
                </div>
                <div class="add add-right" data-class="buttons1">
                    <i class="icon-right-outline"></i>
                </div>
            </div>
            <div class="str0">
            </div>
            <div>
                <div class="str" id="str">
                </div>
                <div class="add add_bottom" id="add-bottom">
                    <i class="icon-down-outline"></i>
                </div>
            </div>
        </div>
        <div class="block-buttons-bot">
        </div>
    </div>
    <br>
    <br>
    <div class="button save-menu">Сохранить</div>
@endsection
