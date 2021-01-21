@extends("developer.template")

@section("title")
    Меню бота
@endsection

@section("h3")
    <h3>Меню бота</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <script src="{{asset('js/developer-panel/edit-menu.js')}}"></script>
    <label for="select-menu">Выберите меню</label>
    <select id="select-menu">
        <option value="">--</option>
        @foreach($menu as $m)
            <option value="{{ substr($m, 0, -5) }}">{{ substr($m, 0, -5) }}</option>
        @endforeach
    </select>
    <br>
    <br>
    <div class="example-menu">

    </div>
@endsection
