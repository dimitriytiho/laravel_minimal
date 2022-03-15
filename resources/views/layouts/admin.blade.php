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
    {{--

    Css files --}}
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.15.4/css/all.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    @if(config('admin.editor') === 'codemirror')
        <!-- Codemirror -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    @endif
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">
    <!-- Bootstrap Switch -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap-switch/css/bootstrap4/bootstrap-switch.min.css') }}">
    {{--


    Здесь можно добавить файлы css через @push('css') --}}
    @stack('css')
    <link rel="stylesheet" href="{{ asset('css/append.css') }}">
    <title>{{ $title ?? Func::site('name') }}</title>
    <meta name="description" content=" " />
</head>
<body class="hold-transition sidebar-mini @if(request()->cookie('sidebar_mini') !== 'full') sidebar-collapse @endif">
<!-- Site wrapper -->
<div class="wrapper">

    @include('admin.inc.header')

    @include('admin.inc.aside')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

                @include('admin.inc.message')

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title ?? null }}</h1>
                    </div>
                    {{--


                    Breadcrumbs --}}
                    @if(request()->path() !== config('add.admin'))
                        <div class="col-sm-6 mt-1">
                            <div class="float-sm-right">
                                @yield('breadcrumbs')
                            </div>
                        </div>
                    @endif
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            @yield('content')
        </section>
    </div>
    <!-- /.content-wrapper -->

    @include('admin.inc.footer')

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<!-- ./wrapper -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
{{--

Js files --}}
<!-- Select2 -->
<script src="{{ asset('lte/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('lte/plugins/select2/js/i18n/ru.js') }}"></script>
<!-- InputMask -->
{{--<script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>--}}
<script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('lte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('lte/plugins/jquery-validation/localization/messages_ru.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('lte/plugins/toastr/toastr.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
{{--


Выбор редактора кода --}}
@if(config('admin.editor') === 'ckeditor')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
@elseif (config('admin.editor') === 'codemirror')
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
@endif
<script>
    var _token = '{{ csrf_token() }}',
        siteName = '{{ Func::site('name') ?: ' ' }}',
        requestPath = '{{ route('admin.main') }}',
        spinner = $('#spinner'),
        spinnerBtn = '<span class="spinner-grow spinner-grow-sm me-2"></span>',

        table = '{{ $info->table ?? null }}',
        currentClass = '{{ $info->class ?? null }}',
        curID = '{{ auth()->user()->id ?? "" }}'

    {!! \App\Support\Locale::translationsJson() !!}
</script>
<script src="{{ asset('js/append.js') }}"></script>
{{--


Здесь можно добавить файлы js через @push('js') --}}
@stack('js')
</body>
</html>
