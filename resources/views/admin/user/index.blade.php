@extends('layouts.admin')
{{--


Breadcrumbs --}}
@section('breadcrumbs')
    {{ Breadcrumbs::render('class') }}
@endsection
{{--


Вывод контента --}}
@section('content')
    <div class="card">
        <div class="card-body">

            @include('admin.inc.search')

            @if($values->isNotEmpty())
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                        <tr>
                            <th scope="col">@lang('a.action')</th>
                            <th scope="col">@lang('a.img')</th>
                            <th scope="col">
                                <span>@lang('a.name')</span>
                                {!! $dbSort::viewIcons('name', $info['view'], $info['kebab']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.email')</span>
                                {!! $dbSort::viewIcons('email', $info['view'], $info['kebab']) !!}
                            </th>
                            <th scope="col">@lang('a.roles')</th>
                            <th scope="col">
                                <span>@lang('a.ip')</span>
                                {!! $dbSort::viewIcons('ip', $info['view'], $info['kebab']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.id')</span>
                                {!! $dbSort::viewIcons('id', $info['view'], $info['kebab']) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($values as $item)
                            @php

                                $roles = $item->getRoleNames()->toArray();

                            @endphp
                            {{--


                            Выделяем активный ряд для роли admin --}}
                            <tr @if(Str::contains($info['model']::getRoleAdmin(), $roles)) class="table-active"@endif>
                                <th scope="row" class="d-flex">
                                    <a href="{{ Route::has("admin.{$info['kebab']}.edit") ? route("admin.{$info['kebab']}.edit", $item->id) :  route("admin.{$info['kebab']}.show", $item->id) }}" class="btn btn-info btn-sm mr-1 pulse" title="@lang('a.edit')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </th>
                                <td>
                                    <img src="{{ $item->img }}" class="img-size-64" alt="{{ $item->title }}">
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @if($roles)
                                        @foreach($roles as $role)
                                            <span title="{{ Func::__($role, 'a') }}">{{ Str::limit(Func::__($role, 'a'), 3) }}</span>&nbsp;
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $item->ip }}</td>
                                <td>{{ $item->id }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h5 class="mt-4">@lang('a.is_nothing_here')</h5>
            @endif
        </div>
        <div class="card-footer">
            @include('admin.inc.pagination')
        </div>
    </div>
@endsection
