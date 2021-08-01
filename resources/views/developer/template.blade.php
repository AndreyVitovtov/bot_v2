<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title") | @lang('pages.developer')</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('css/developer.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.json-browse.css')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/common.js')}}"></script>
    <script src="{{asset('js/jquery.cookie.js')}}"></script>
    <script src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>
    <script src="{{asset('js/charts/Chart.js')}}"></script>
    <script src="{{asset('js/jquery.json-browse.js')}}"></script>
    <script src="{{asset('js/jquery-ui.js')}}"></script>

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
        @lang('pages.bot_name')
    </section>
    <section class="right-panel">
{{--        <nav class="open-menu mob-hidden">--}}
{{--            <i class="icon-menu"></i>--}}
{{--        </nav>--}}
        <div></div>
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
                    @lang('pages.developer')
                </div>
            </div>
            <div class="dropdown-menu-nav">
                <div>
                    <button class="button user-settings">@lang('pages.top_panel_settings')</button>
                </div>
                <div>
                    <button data-go="exit" class="button">@lang('pages.top_panel_log_off')</button>
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
                @lang('pages.developer')
            </span>
        </div>
        <ul class="sidebar-menu">
            <li class="header">@lang('pages.menu')</li>
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
            Copyright © 2021 <a href="https://vitovtov.top" target="_blank">vitovtov.top</a>
        </footer>
    </section>
</main>

{{--@if(isset($menuItem))--}}
{{--    <script>--}}
{{--        $('.item-menu').removeClass('active');--}}
{{--        $('main section.sidebar .menu-hidden div').removeClass('active');--}}
{{--        $('.{{ $menuItem }}').addClass('active');--}}
{{--        $('.{{ $menuItem }}').parents('span.rolled-hidden').children('li.item-menu').addClass('active');--}}
{{--        $('.{{ $menuItem }}').parents('li').addClass('menu-active');--}}
{{--        $('.{{ $menuItem }}').parents('li').toggle();--}}
{{--    </script>--}}
{{--@endif--}}

{{--@if(isset($notification))--}}
{{--    <script>--}}
{{--        setTimeout(function() {--}}
{{--            popUpWindow('{{ $notification }}')--}}
{{--        }, 300);--}}
{{--    </script>--}}
{{--@endif--}}

{{--<script>--}}
{{--    $('body').on('click', '.open-menu', function() {--}}
{{--        rolled();--}}
{{--    });--}}

{{--    if($.cookie('rolled') === 'true') {--}}
{{--        $('header .right-panel').css('width: calc( 100% - 50px )');--}}
{{--        $('main section.sidebar .menu-hidden.menu-active').hide();--}}
{{--    }--}}

{{--    function rolled() {--}}
{{--        if($('.sidebar').is('.rolled')) {--}}
{{--            $('header .left-panel').html('@lang('template.left_panel_panel')');--}}
{{--            $('main section.sidebar .menu-hidden.menu-active').show();--}}

{{--            $.cookie('rolled', 'false', {path: '/'});--}}
{{--        }--}}
{{--        else {--}}
{{--            $('header .left-panel').html('@lang('template.panel_p')');--}}
{{--            $('header .right-panel').css('width: calc( 100% - 50px )');--}}
{{--            $('main section.sidebar .menu-hidden.menu-active').hide();--}}

{{--            $.cookie('rolled', 'true', {path: '/'});--}}
{{--        }--}}
{{--        $('.sidebar').toggleClass('rolled');--}}
{{--        $('header .left-panel').toggleClass('rolled');--}}
{{--        $('header .right-panel').toggleClass('rolled');--}}
{{--    }--}}
{{--</script>--}}
</body>
</html>
