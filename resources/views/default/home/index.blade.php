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
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <div class="mt-3">
                        <a href="{{ route('logout-get') }}">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{--

Подключается блок footer --}}
@section('footer')
    @include("{$view}.inc.footer")
@endsection
