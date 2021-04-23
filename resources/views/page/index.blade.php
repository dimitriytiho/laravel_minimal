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
    <main class="main">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="display-4 text-secondary">Привет, мир!</h1>
                        <p class="lead">Это простой пример блока с компонентом в стиле jumbotron для привлечения дополнительного внимания к содержанию или информации.</p>
                        <hr class="my-4">
                        <p>Использются служебные классы для типографики и расстояния содержимого в контейнере большего размера.</p>
                        <p class="lead">
                            <a class="btn btn-primary" href="#" role="button">Подробнее</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col border py-4 px-5 my-4">
                    <h3 class="h5 font-weight-bold mb-3">Повседневная практика показывает</h3>
                    <p class="mb-0">Задача организации, в особенности же новая модель организационной деятельности в значительной степени обуславливает создание направлений прогрессивного развития. С другой стороны консультация с широким активом позволяет оценить значение модели развития. Разнообразный и богатый опыт постоянное информационно-пропагандистское обеспечение нашей деятельности представляет собой интересный эксперимент проверки дальнейших направлений развития. Товарищи! укрепление и развитие структуры позволяет оценить значение форм развития.</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col">

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
