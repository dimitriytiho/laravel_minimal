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
                            <th scope="col">
                                <span>@lang('a.key')</span>
                                {!! $dbSort::viewIcons('key', $info->view, $info->kebab) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.value')</span>
                                {!! $dbSort::viewIcons('value', $info->view, $info->kebab) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.type')</span>
                                {!! $dbSort::viewIcons('type', $info->view, $info->kebab) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.section')</span>
                                {!! $dbSort::viewIcons('section', $info->view, $info->kebab) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.id')</span>
                                {!! $dbSort::viewIcons('id', $info->view, $info->kebab) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($values as $item)
                            <tr @if($item->status && $item->status !== $active) class="table-active"@endif>
                                <th scope="row" class="d-flex">
                                    <a href="{{ Route::has("admin.{$info->kebab}.edit") ? route("admin.{$info->kebab}.edit", $item->id) :  route("admin.{$info->kebab}.show", $item->id) }}" class="btn btn-info btn-sm mr-1 pulse" title="@lang('a.edit')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </th>
                                <td>{{ $item->key }}{{--{{ Func::__($item->key, 'a') }}--}}</td>
                                <td>{{ Str::limit($item->value, 100) }}</td>
                                <td>@lang('a.' . $item->type)</td>
                                <td>{{ $item->section }}</td>
                                <td>{{ $item->id }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h5 class="mt-4">@lang('a.is_nothing_here')</h5>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <span>@lang('a.example_use_in_views')</span>
                    <b>@{{ Func::site('name') }}</b>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('admin.inc.pagination')
        </div>
    </div>
@endsection
