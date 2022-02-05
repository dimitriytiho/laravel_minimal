@section('titleSeo')@lang('s.Preventive_work')@endsection
{{--

Наследуем шаблон --}}
@extends("layouts.{$view}")
{{--

Подключается блок header --}}
{{--@section('header')
    @include("{$view}.inc.header")
@endsection--}}
{{--


Вывод контента

--}}
@section('content')
    <main class="main text-center">
        <div class="container mb-md-5">
            <div class="row justify-content-center">
                <div class="col-md-7 mt-0 mt-md-4">

                    <i class="fas fa-cog fa-7x fa-spin text-primary py-5"></i>

                    <h1 class="h4 pt-4">@lang('s.Preventive_work_go')</h1>

                    @if(Func::site('email'))
                        <p class="my-5">{!! __('s.Preventive_work_contact', ['email' => Func::site('email') ?: ' ']) !!}@if(Func::site('tel')) @lang('s.or_call') {{ Func::site('tel') }}@endif.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
{{--

Подключается блок footer --}}
{{--
@section('footer')
    @include("{$view}.inc.footer")
@endsection
--}}
