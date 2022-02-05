<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('index') }}">{{ Func::site('name') ?: 'OmegaKontur' }}</a>
        <a href="{{ route('index') }}" class="d-block mt-2">
            <picture>
                <source srcset="{{ asset("{$img}/logo/logotype.svg") }}">
                <img src="{{ asset("{$img}/logo/logotype.png") }}" alt="{{  Func::site('name') }}">
            </picture>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="hamburger"></span>
            <span class="hamburger"></span>
            <span class="hamburger"></span>
        </button>

        @empty($noShowErrorPage)
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('index') }}/kontakty">Контакты</a>
                    </li>
                    @if(Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">@lang('s.login')</a>
                        </li>
                    @endif
                    @if(auth()->check() && auth()->user()->hasRole(\App\Services\Auth\Role::ADMIN_PANEL_NAMES))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.main') }}">@lang('a.dashboard')</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endempty
    </nav>
</header>
