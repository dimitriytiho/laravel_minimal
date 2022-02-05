{{--

Если у пользователя устаревший браузер, то выведется сообщение --}}
<!--[if lt IE 9]>
<link rel="stylesheet" href="{{ asset('css/reject.css') }}">
<script src="{{ asset('js/reject.min.js') }}" data-text="@lang('s.browser_you_are_using_is_outdated')"></script>
<![endif]-->
{{--

Если у пользователя выключен JS, то выведется сообщение --}}
{{--
<noscript>
    <div class="container mt-4 mb-2">
        <div class="row">
            <div class="col">
                <div class="alert alert-danger p-3">@lang('s.Please_enable_JavaScript')</div>
            </div>
        </div>
    </div>
</noscript>
--}}