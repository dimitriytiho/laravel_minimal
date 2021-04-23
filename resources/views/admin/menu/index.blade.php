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
                                <span>@lang('a.title')</span>
                                {!! $dbSort::viewIcons('title', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.slug')</span>
                                {!! $dbSort::viewIcons('slug', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.status')</span>
                                {!! $dbSort::viewIcons('status', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.sort')</span>
                                {!! $dbSort::viewIcons('sort', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.parent_id')</span>
                                {!! $dbSort::viewIcons('parent_id', $info['view'], $info['slug']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.id')</span>
                                {!! $dbSort::viewIcons('id', $info['view'], $info['slug']) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($values as $item)
                            <tr @if($item->status && $item->status !== $active) class="table-active"@endif>
                                <th scope="row" class="d-flex">
                                    <a href="{{ Route::has("admin.{$info['view']}.edit") ? route("admin.{$info['slug']}.edit", $item->id) :  route("admin.{$info['slug']}.show", $item->id) }}" class="btn btn-info btn-sm mr-1 pulse" title="@lang('a.edit')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </th>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>@lang('a.' . $item->status)</td>
                                <td>{{ $item->sort }}</td>
                                <td>{{ $item->parent_id }}</td>
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
