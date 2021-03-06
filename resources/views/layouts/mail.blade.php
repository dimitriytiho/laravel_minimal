@php


    $siteName = Func::site('name');
    $color = config('add.primary', '#ccc');
    $email = Func::site('email');
    $tel = Func::site('tel') ? __('s.or_call') . Func::site('tel') : null;


@endphp
    <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? $subject }}</title>
</head>
<body>
@isset($title)
    <h1 style="font-size: 36px; font-weight: lighter; color: {{ $color }};">{{ $title }}</h1>
    <br>
@endisset
<div style="font-size: 16px;">{!! $body ?? null !!}</div>
<br>
<br>
@isset($title)
    <p style="font-size: 14px; font-weight: lighter;">@lang('s.Please_do_not_reply_to_this_email') @if($email) @lang('s.Please_contact_us')<a href="mailto:{{ $email }}" style="color: {{ $color }}; text-decoration: none;">{{ $email }}</a>@endif {{ $tel }}</p>
    <br>
    <p style="font-size: 16px;">@lang('s.Best_regards')<a href="{{ route('index') }}" style="color: {{ $color }}; text-decoration: none;">{{ $siteName }}</a></p>
@endisset
</body>
</html>
