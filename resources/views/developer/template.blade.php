<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title") | Разработка</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('css/developer.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.json-browse.css')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/admin-panel/common.js')}}"></script>
    <script src="{{asset('js/admin-panel/drawChart.js')}}"></script>
    <script src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>
    <script src="{{asset('js/admin-panel/jquery.json-browse.js')}}"></script>

    @if(isset($menuItem))
        <script>
            window.onload = function() {
                $('.item-menu').removeClass('active');
                $('main section.sidebar .menu-hidden div').removeClass('active');
                $('.{{ $menuItem }}').addClass('active');
                $('.{{ $menuItem }}').parents('span.rolled-hidden').children('li.item-menu').addClass('active');
                $('.{{ $menuItem }}').parents('li').addClass('menu-active');
                $('.{{ $menuItem }}').parents('li').toggle();
            };
        </script>
    @endif
</head>
<body>
<header>
    <section class="left-panel">
        Панель
    </section>
    <section class="right-panel">
        <nav class="open-menu mob-hidden">
            <i class="icon-menu"></i>
        </nav>
        <nav class="open-menu-mob pc-hidden">
            <i class="icon-menu"></i>
        </nav>
        <nav class="open-user-menu">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <span>
					Разработчик
				</span>
        </nav>
        <div class="dropdown-menu">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <div class="title">
                <div>
                    Разработчик
                </div>
            </div>
            <div class="dropdown-menu-nav">
                <div>
                    <button class="button user-settings">Настройки</button>
                </div>
                <div>
                    <button data-go="exit" class="button">Выйти</button>
                </div>
            </div>
        </div>
    </section>
</header>
<main>
    <section class="sidebar no-active">
        <div class="user-panel">
            <div class="avatar-user-panel"></div>
            <span>
                Разработчик
            </span>
        </div>
        <ul class="sidebar-menu">
            <li class="header">Меню</li>
            @include("developer.menu")
        </ul>
    </section>
    <section class="content">
        <div class="container">
            @yield("h3")
            <div>
                @yield("main")
            </div>
        </div>
        <footer>
            Copyright © 2020 <a href="https://vitovtov.info" target="_blank">vitovtov.info</a>
        </footer>
    </section>
</main>
</body>
</html>
