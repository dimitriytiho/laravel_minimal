@php


use App\Models\User;


@endphp
{{--

@section('titleSeo') Test title @endsection В любом месте переопределить titleSeo

@section('description') Test description @endsection В любом месте переопределить description



Основной шаблон по-умолчанию --}}
<!doctype html>
<html lang="{{ app()->getLocale() }}" class="no-js">
<head>
    <meta name="theme-color" content="{{ config('add.primary') ?: '#ccc' }}">
    {{--

    Preloader --}}
    <script src="{{ asset('js/preloader.min.js') }}"></script>
    <meta charset="utf-8">
    {{--

    Если не нужно индексировать сайт, то true, если нужно, то false --}}
    @if(!config('add.not_index_website'))
        <meta name="robots" content="index, follow" />
    @endif
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('touch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('touch-icon-ipad-retina.png') }}">
    <link rel="cononical" href="@section('cononical'){{ request()->url() }}@show">
    {{--

    Fonts website --}}
    {{--<link href="//fonts.googleapis.com/css?family=Roboto:300,400,700&amp;subset=cyrillic" rel="stylesheet">--}}
    {{--

    Fontawesome --}}
    <link href="//use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@section('titleSeo'){{ $titleSeo ?? $title ?? Func::site('name') }}@show | {{ Func::site('name') }}</title>
    <meta name="description" content="@section('description'){{ $description ?? ' ' }}@show" />
    @if(!empty($keywords))
        <meta name="keywords" content="{{ $keywords }}" />
    @endif
    {{--

    Bootstrap --}}
    {{--<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">--}}
    @include('inc.warning')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{--

    Здесь можно добавить файлы css через @section('css') --}}
    @yield('css')
</head>
<body>
{{--

Панель администратора --}}
@include('inc.panel_dashboard')
<div class="app" id="app">
    <div class="content-block">
        @yield('header')

        @include('inc.message')

        <div class="content" id="content">
            @yield('content')
        </div>
        <div id="bottom-block"></div>
    </div>

    <div class="footer-block">
        @yield('footer')
    </div>
</div>
{{--

Стрелка вверх --}}
{{--<div class="btn btn-primary pulse scale-out" id="btn_up" aria-label="@lang('s.move_to_top')">
    <i class="fas fa-arrow-up"></i>
</div>--}}
{{--

Прелодер спинер --}}
{{--<div id="spinner">
    <div class="spinner-block">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>--}}
{{--

JS


Modernizr Webp --}}
{{--<script src="{{ asset('js/modernizr3.6.0.webp.js') }}"></script>--}}
{{--

Google ReCaptcha --}}
{{--@if(config('add.recaptcha_public_key'))
    --}}{{--

    ReCaptcha v2 --}}{{--
    --}}{{--<script src="//www.google.com/recaptcha/api.js"></script>--}}{{--
    --}}{{--

    ReCaptcha v3 --}}{{--
    <script src="//www.google.com/recaptcha/api.js?render={{ config('add.recaptcha_public_key') }}"></script>
@endif
<script>
    var recaptchaV = 3,
        recaptchaKey = '{{ config('add.recaptcha_public_key') }}'
</script>--}}
{{--<script src="{{ asset('js/before.js') }}"></script>--}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{--

Bootstrap --}}
<script src="//cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
{{--


CDN ленивой загрузки картинок --}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>--}}
{{--


InputMask --}}
<script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
{{--


jquery-validation --}}
<script src="{{ asset('lte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('lte/plugins/jquery-validation/localization/messages_ru.min.js') }}"></script>

{{--@if(!request()->is('/'))--}}
    {{--

    Подсказки Bootstrap --}}
    {{--<script src="//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous" defer></script>--}}
    {{--

    Fancybox --}}
    {{--<script src="{{ asset('js/fancybox/jquery.fancybox.min.js') }}" defer></script>
    <script src="{{ asset('js/fancybox/localization/ru.min.js') }}" defer></script>--}}
{{--@endif--}}
{{--<script src="{{ asset('js/svg4everybody.min.js') }}"></script>--}}
<script>
    {{--svg4everybody()--}}
    {{-- Поддержка Svg из sprite во всех браузерах
    https://github.com/jonathantneal/svg4everybody --}}

    var _token = '{{ session()->token() }}',
        path = '{{ route('index') }}',
        site_name = '{{ Func::site('name') ?: ' ' }}',
        site_tel = '{{ Func::site('tel') ?: ' ' }}',
        site_email = '{{ Func::site('email') ?: ' ' }}',
        img_path = '{{ $img }}',
        img_file = '{{ $file }}',
        main_color = '{{ config('add.primary') ?? '#ccc' }}',
        spinner = $('#spinner'),
        spinnerBtn = '<span class="spinner-grow spinner-grow-sm mr-2"></span>'
</script>
{{--

Если в контенте из БД есть скрипты, то они выведятся здесь, через метод Main::getDownScript() --}}
{{--@if(Func::get('scripts'))
    @foreach(Func::get('scripts') as $script)
        {!! $script . PHP_EOL !!}
    @endforeach
@endif--}}
{{--

Вывод js кода из вида pages.contact_us --}}
{{--@stack('novalidate')--}}
<script src="{{ asset('js/app.js') }}" defer></script>
{{--

Здесь можно добавить файлы js --}}
@yield('js')
{{--

Все счётчики для сайта поместить в этот файл, не показываем на локальной машине и для админов --}}
@if(!(app()->environment() !== 'production' || auth()->check() && auth()->user()->hasRole(User::getRoleAdmin())))
    @include('inc.analytics')
@endif
</body>
</html>
