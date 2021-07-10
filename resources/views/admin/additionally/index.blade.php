@extends('layouts.admin')
{{--


Breadcrumbs --}}
@section('breadcrumbs')
    {{ Breadcrumbs::render('class') }}
@endsection
{{--

Вывод контента

--}}
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">@lang('a.cache')</div>
        </div>
        <div class="card-body">
            <div class="row">

                {!! $html::infoBox('info', 'fas fa-database', 'db_caches', 'remove', 'admin.additionally', 'cache=db', 'confirm_link') !!}

                {!! $html::infoBox('success', 'far fa-star', 'view_caches', 'remove', 'admin.additionally', 'cache=views', 'confirm_link') !!}

                {!! $html::infoBox('warning', 'far fa-flag', 'route_caches', 'remove', 'admin.additionally', 'cache=routes', 'confirm_link') !!}

                {!! $html::infoBox('danger', 'fas fa-cog', 'config_caches', 'remove', 'admin.additionally', 'cache=config', 'confirm_link') !!}

            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="card-title">@lang('a.every')</div>
        </div>
        <div class="card-body">
            <div class="row">

                {!! $html::infoBox('maroon', 'fas fa-project-diagram', 'refresh_seo', 'run', 'admin.additionally', 'upload=run', 'confirm_link', 'col-md-6') !!}

            </div>
        </div>
    </div>
@endsection
