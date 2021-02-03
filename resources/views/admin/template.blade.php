<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title") | @lang('template.title')</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/developer.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/jquery.cookie.js')}}"></script>
    <script src="{{asset('js/common.js')}}"></script>
    <script src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>
    <script src="{{asset('js/charts/Chart.js')}}"></script>

</head>
<body>
<div class="pop-up-window">
</div>
<header>
    <section class="left-panel @if(isset($_COOKIE['rolled']) && $_COOKIE['rolled'] === 'true') rolled @endif">
        @if(isset($_COOKIE['rolled']) && $_COOKIE['rolled'] === 'true')
            {{ Lang::get('pages.bot_name')[0] }}
        @else
            @lang('pages.bot_name')
        @endif
    </section>
    <section class="right-panel @if(isset($_COOKIE['rolled']) && $_COOKIE['rolled'] === 'true') rolled @endif">
        <nav class="open-menu mob-hidden">
            <i class="icon-menu"></i>
        </nav>
        <nav class="open-menu-mob pc-hidden">
            <i class="icon-menu"></i>
        </nav>
        <div class="right-panel-lang-user">
            <div class="languages">
                <div>
                    <a href="{{ route('locale', App::getLocale()) }}">
                        <img src="{{ url('/img/language/'.App::getLocale().'.png') }}" alt="">
                    </a>
                </div>
                <div class="languages-other">
                    @if(App::getLocale() == "ru")
                        <a href="{{ route('locale', 'us') }}">
                            <img src="{{ url('/img/language/us.png') }}" alt="">
                        </a>
                    @else
                        <a href="{{ route('locale', 'ru') }}">
                            <img src="{{ url('/img/language/ru.png') }}" alt="">
                        </a>
                    @endif
                </div>
            </div>
            <nav class="open-user-menu">
                <img src="{{asset('img/avatar5.png')}}" alt="avatar">
                <span>
                    {{ Auth::user()->name }}
            </span>
            </nav>
        </div>
        <div class="dropdown-menu">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <div class="title">
                <div>
                    {{ Auth::user()->name }}
                </div>
            </div>
            <div class="dropdown-menu-nav">
                <div>
                    <form action="/admin/settings" method="POST">
                        @csrf
                        <button class="button user-settings">@lang('template.top_panel_settings')</button>
                    </form>
                </div>
                <div>
                    <form action="/logout">
                        <button data-go="exit" class="button">@lang('template.top_panel_log_off')</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</header>
<main>
    <section class="sidebar no-active @if(isset($_COOKIE['rolled']) && $_COOKIE['rolled'] === 'true') rolled @endif">
        <div class="user-panel">
            <div class="avatar-user-panel"></div>
            <span>
                @lang('template.left_panel_administrator')
            </span>
        </div>
        <ul class="sidebar-menu">
            <li class="header">@lang('template.left_panel_menu')</li>
            @include('admin.menu')
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
            Copyright © 2021 <a href="https://vitovtov.top" target="_blank">vitovtov.top</a>
        </footer>
    </section>
</main>

@if(isset($menuItem))
    <script>
        $('.item-menu').removeClass('active');
        $('main section.sidebar .menu-hidden div').removeClass('active');
        $('.{{ $menuItem }}').addClass('active');
        $('.{{ $menuItem }}').parents('span.rolled-hidden').children('li.item-menu').addClass('active');
        $('.{{ $menuItem }}').parents('li').addClass('menu-active');
        $('.{{ $menuItem }}').parents('li').toggle();
    </script>
@endif

@if(isset($notification))
    <script>
        setTimeout(function() {
            popUpWindow('{{ $notification }}')
        }, 300);
    </script>
@endif

<script>
    $('body').on('click', '.open-menu', function() {
        rolled();
    });

    if($.cookie('rolled') === 'true') {
        $('header .right-panel').css('width: calc( 100% - 50px )');
        $('main section.sidebar .menu-hidden.menu-active').hide();
    }

    function rolled() {
        if($('.sidebar').is('.rolled')) {
            $('header .left-panel').html('@lang('pages.bot_name')');
            $('main section.sidebar .menu-hidden.menu-active').show();

            $.cookie('rolled', 'false', {path: '/'});
        }
        else {
            $('header .left-panel').html('{{ Lang::get('pages.bot_name')[0] }}');
            $('header .right-panel').css('width: calc( 100% - 50px )');
            $('main section.sidebar .menu-hidden.menu-active').hide();

            $.cookie('rolled', 'true', {path: '/'});
        }
        $('.sidebar').toggleClass('rolled');
        $('header .left-panel').toggleClass('rolled');
        $('header .right-panel').toggleClass('rolled');
    }
</script>
</body>
</html>
