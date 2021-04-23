<footer class="container-fluid footer text-white-50">
    <div class="row">
        <div class="col w-100 bg-light pt-2"></div>
    </div>
    <div class="row bg-dark py-4 px-5 footer__content">
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ route('index') }}" class="d-block mt-2">
                <picture>
                    <source srcset="{{ asset("{$img}/logo/logotype.svg") }}">
                    <img src="{{ asset("{$img}/logo/logotype.png") }}" alt="{{ Func::site('name') }}">
                </picture>
            </a>
        </div>
        @empty($noShowErrorPage)
            <div class="col-xl-2 col-lg-4 col-sm-6 font-weight-light">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 1 text 1</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 1 text 2</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 1 text 3</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 1 text 4</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-lg-4 col-sm-6 font-weight-light">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 2 text 1</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 2 text 2</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 2 text 3</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 2 text 4</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-lg-4 col-sm-6 font-weight-light">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 3 text 1</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 3 text 2</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 3 text 3</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 3 text 4</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-lg-4 col-sm-6 font-weight-light">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 4 text 1</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 4 text 2</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 4 text 3</a>
                    </li>
                    <li>
                        <a href="#" class="text-white-50 d-block py-1">Menu 4 text 4</a>
                    </li>
                </ul>
            </div>
        @endempty
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <h5 class="mt-2 mb-4">{{ Func::site('tel') }}</h5>
            <p>c 10:00 до 22:00</p>
        </div>
    </div>
    <div class="row">
        <div class="col font-weight-light bg-dark text-center py-2">
            <small>&copy;&nbsp;{{ date('Y') }}&nbsp;{{ Func::site('name') }}</small>
        </div>
    </div>
</footer>
