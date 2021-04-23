<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset(config('add.img') . '/omegakontur/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset(config('add.img') . '/omegakontur/touch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/omegakontur/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset(config('add.img') . '/omegakontur/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset(config('add.img') . '/omegakontur/touch-icon-ipad-retina.png') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/append.css') }}">

    <title>{{ $title ?? Main::site('name') }}</title>
    <meta name="description" content=" " />
</head>
<body class="hold-transition login-page">

<div class="my-2">
    @include('admin.inc.message')
</div>

<section class="content">
    @yield('content')
</section>
{{--

jQuery --}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{--


Google ReCaptcha, если есть в настройках ключи --}}
@if(config('add.env') !== 'local' && config('add.recaptcha_public_key'))
    <script src="//www.google.com/recaptcha/api.js?render={{ config('add.recaptcha_public_key') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ config('add.recaptcha_public_key') }}", {action: 'submit'}).then(function(token) {
                $('input[name="g-recaptcha-response"]').val(token)
            })
        })
    </script>
@endif
{{--

Bootstrap --}}
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
{{--

Jquery-validation --}}
<script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('lte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('lte/plugins/jquery-validation/localization/messages_ru.min.js') }}"></script>
{{--

Admin LTE --}}
<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
<script>

    var _token = '{{ session()->token() }}',
        spinner = $('#spinner'),
        requestPath = '',
        spinnerBtn = '<span class="spinner-grow spinner-grow-sm mr-2"></span>'

    {!! \App\Helpers\Locale::translationsJson() !!}
</script>
<script src="{{ asset('js/append.js') }}"></script>
{{--


Вывод scripts из видов --}}
@yield('scripts')
</body>
</html>
