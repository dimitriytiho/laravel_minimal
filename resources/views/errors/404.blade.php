@section('titleSeo')@lang('s.page_not_found')@endsection
{{--

Наследуем шаблон --}}
@extends('layouts.app')
{{--

Подключается блок header --}}
@section('header')
    @include('inc.header')
@endsection
{{--


Вывод контента

--}}
@section('content')
    <main class="main text-center">
        <div class="container mb-md-5">
            <div class="row justify-content-center">
                <div class="col-md-7 mt-0 mt-md-4">

                    <i class="far fa-compass fa-7x fa-spin text-primary py-5"></i>

                    <h1 class="h4 pt-4">@lang('s.whoops_no_page')</h1>

                    <p class="my-5">@lang('s.you_can_go') <a href="javascript:history.back()">{{ Str::lower(__('s.back')) }},</a> @lang('s.or_go')<a href="{{ route('index') }}">@lang('s.to_home_page').</a></p>
                </div>
            </div>
        </div>
    </main>
@endsection
{{--

Подключается блок footer --}}
@section('footer')
    @include('inc.footer')
@endsection
