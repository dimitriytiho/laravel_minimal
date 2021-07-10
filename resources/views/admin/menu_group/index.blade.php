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
                                {!! $dbSort::viewIcons('title', $info['view'], $info['kebab']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.sort')</span>
                                {!! $dbSort::viewIcons('sort', $info['view'], $info['kebab']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.id')</span>
                                {!! $dbSort::viewIcons('id', $info['view'], $info['kebab']) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($values as $item)
                            <tr>
                                <th scope="row" class="d-flex">
                                    <a href="{{ route("admin.{$info['kebab']}.edit", $item->id) }}" class="btn btn-info btn-sm mr-1 pulse" title="@lang('a.edit')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </th>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->sort }}</td>
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
