@php

    use App\Models\Menu;



    // Имя роли Admin
    $adminRoleName = \App\Providers\AuthServiceProvider::ROLE_ADMIN;

    // Левое меню, получаем по belong_id = 2  и кэшируем
    $leftMenu = cache()->rememberForever('admin_left_menu', function () {
        return Menu::whereBelongId(2)
        ->active()
        ->orderBy('sort')
        ->orderBy('id')
        ->get()
        ->toTree();
    });


@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ session()->get('back_link_site', route('index')) }}" class="brand-link">
        <img src="{{ asset("{$img}/omegakontur/touch-icon-iphone-retina.png") }}" alt="{{ config('add.dev') }}" class="brand-image img-circle">
        <span class="brand-text font-weight-light">@lang('a.website')</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(!empty($leftMenu))
                    @foreach($leftMenu as $key => $item)
                        @php


                            // Добавил текущий slug
                            $childSlugs[] = $item->slug;

                            // Получим slug для всем детей
                            if ($item->children && $item->children->isNotEmpty()) {
                                foreach ($item->children as $child) {
                                    $childSlugs[] = $child->slug;
                                }
                            }


                        @endphp
                        {{--


                        Не покажем элемент меню, если не админ --}}
                        @continue(
                            !auth()->user()->can(Str::kebab($item->class))
                            //!auth()->user()->hasRole($adminRoleName) && auth()->user()->hasAnyPermission([$item->class])
                            //!auth()->user()->hasRole($adminRoleName) && Str::contains($item->class, ['Log'])
                        )
                        <li class="nav-item @if(


    // Определим активный пунк меню
    $item->slug === '/' && request()->path() === config('add.admin')
    || $item->slug !== '/' && Str::contains(request()->path(), $childSlugs)

    ) menu-is-opening menu-open active @endif">
                            @php

                                // Очистим массив
                                $childSlugs = [];

                            @endphp
                            <a href="/{{ config('add.admin') . $item->slug }}" class="nav-link">
                                <i class="nav-icon {{ $item->item }}"></i>
                                <p>
                                    @lang("a.{$item->title}")
                                    @if($item->children && $item->children->isNotEmpty())
                                        <i class="right fas fa-angle-left"></i>
                                    @endif
                                    {{--


                                    Для показа кол-ва для нужного элемента получите его в Admin/AppController --}}
                                    @if(!empty($countTable[$item->title]))
                                        <span class="badge badge-info right">{{ $countTable[$item->title] }}</span>
                                    @endif
                                </p>
                            </a>
                            {{--


                            Вложенный цикл --}}
                            @if($item->children && $item->children->isNotEmpty())
                                <ul class="nav nav-treeview">
                                    @foreach($item->children as $child)
                                        {{--


                                        Не покажем элемент меню, если не админ --}}
                                        @continue(
                                            !auth()->user()->can(Str::kebab($child->class))
                                            //!auth()->user()->hasRole($adminRoleName) && auth()->user()->hasAnyPermission([$child->class])
                                            //!auth()->user()->hasRole($adminRoleName) && Str::contains($child->class, ['Log'])
                                        )
                                        <li class="nav-item">
                                            <a href="/{{ config('add.admin') . $child->slug }}" class="nav-link @if(
                                        // Активный элемент
                                        str_replace(config('add.admin'), '', request()->path()) === $child->slug
                                        || '/' . request()->segment(2) === $child->slug
                                        && !empty(request()->segment(4))

                                        ) active @endif">
                                                <i class="{{ $child->item }} nav-icon"></i>
                                                <p>{{ Func::__($child->title, 'a') }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
