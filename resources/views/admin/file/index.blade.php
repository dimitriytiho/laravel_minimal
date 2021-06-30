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
                                {!! $dbSort::viewIcons('name', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.path')</span>
                                {!! $dbSort::viewIcons('path', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.old_name')</span>
                                {!! $dbSort::viewIcons('old_name', $info['view'], $info['view']) !!}
                            </th>
                            <th scope="col">
                                <span>@lang('a.type')</span>
                                {!! $dbSort::viewIcons('type', $info['view'], $info['view']) !!}
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
                                    <form action="{{ route("admin.{$info['slug']}.destroy", $item->id) }}" method="post" class="confirm_form">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm pulse" title="@lang('a.remove')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </th>
                                <td>{!! \App\Support\Admin\Attachment::previewFile($item) !!}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->path }}</td>
                                <td>{{ $item->old_name }}</td>
                                <td>{{ $item->type }}</td>
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
