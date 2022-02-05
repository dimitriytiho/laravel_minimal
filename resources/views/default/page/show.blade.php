{{--

Наследуем шаблон --}}
@extends("layouts.{$view}")
{{--

Подключается блок header --}}
@section('header')
    @include("{$view}.inc.header")
@endsection
{{--


Вывод контента

--}}
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="font-weight-light text-secondary mt-5">{{ $values->title ?? null }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col my-4">
                    {!! $values->body ?? null !!}
                </div>
            </div>
        </div>
    </main>
@endsection
{{--

Подключается блок footer --}}
@section('footer')
    @include("{$view}.inc.footer")
@endsection
