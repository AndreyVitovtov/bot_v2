@extends('layouts.app')

@section('main')
    <link rel="stylesheet" href="{{asset('css/auth.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
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
    <h3><b>@lang('auth.authorization')</b></h3>
    <div class="auth">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <input type="text" name="login" id="inputLogin" placeholder="@lang('auth.login')" value="{{ old('login') }}" required autofocus>
                <i class="icon-user-8"></i>
            </div>
            <div>
                <input type="password" name="password" id="inputPassword" placeholder="@lang('auth.password')">
                <i class="icon-lock-filled"></i>
            </div>
            <button id="login">@lang('auth.sign_in')</button>
        </form>
    </div>
@endsection
