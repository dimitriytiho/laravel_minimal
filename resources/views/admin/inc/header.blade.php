<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a
                href="#"
                class="nav-link link_click"
                role="button"
                @if($isMobile ?? null)
                    data-widget="pushmenu"
                @else
                    data-url="{{ route('admin.sidebar-mini') }}"
                    data-val="@if(request()->cookie('sidebar_mini') === 'full') mini @else full @endif"
                @endif
            >
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        {{--<li class="nav-item bell_danger {{ empty($countTable['orderNew']) ? 'js-none' : null }}">
            <a href="{{ route('admin.order.index') }}" class="nav-link">
                <i class="fas fa-bell text-danger"></i>
                <span class="badge badge-info navbar-badge">{{ $countTable['orderNew'] ?? 1 }}</span>
            </a>
        </li>--}}
        @if(config('admin.locales') && count(config('admin.locales')) > 1)
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-globe"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @foreach(config('admin.locales') as $locale)
                        <a href="{{ route('admin.locale', $locale) }}" class="dropdown-item"><b>{{ $locale }}</b> @lang("a.{$locale}")</a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                </div>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a {{ auth()->user()->hasRole($adminRoleName) ? 'href=' . route('admin.user.edit', auth()->user()->id) : null }} class="dropdown-item">
                    <div class="media">
                        <img src="{{ asset(auth()->user()->img ?? config('add.imgDefault')) }}" alt="{{ auth()->user()->name }}" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">{{ auth()->user()->name }}</h3>
                            <p class="text-sm">@lang('a.profile')</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout-get') }}" class="dropdown-item">@lang('a.exit')</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
