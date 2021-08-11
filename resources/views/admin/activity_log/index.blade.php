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
            {{--


            Поиск --}}
            <div class="row">
                @if(!empty($roles))
                    <div class="col-md-2 col-sm-3 mb-2">
                        {{ $form::select('roles', $roles, [
                            'label' => 'false',
                            'lang' => 'false',
                            'class' => 'custom-select custom-select-sm select_change',
                            'data-url' => route('admin.get-session'),
                            'data-key' => 'log_role'
                        ], session('log_role')) }}
                    </div>
                @endif
                @if(!empty($users))
                    <div class="col-md-2 col-sm-3 mb-2">
                        {{ $form::select('users', $users, [
                            'label' => 'false',
                            'lang' => 'false',
                            'class' => 'custom-select custom-select-sm select_change',
                            'data-url' => route('admin.get-session'),
                            'data-key' => 'log_user'
                        ], session('log_user'), false, false, null, null, true) }}
                    </div>
                @endif
                @if(!empty($tags))
                    <div class="col-md-2 col-sm-3 mb-2">
                        {{ $form::select('tags', $tags, [
                            'label' => 'false',
                            'lang' => 'false',
                            'class' => 'custom-select custom-select-sm select_change',
                            'data-url' => route('admin.get-session'),
                            'data-key' => 'log_tag'
                        ], session('log_tag')) }}
                    </div>
                @endif

                @include('admin.inc.search_pagination')
            </div>

            @if($values->isNotEmpty())
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                        <tr>
                            <th scope="col">@lang('a.tag')</th>
                            <th scope="col">@lang('a.description')</th>
                            <th scope="col">@lang('a.created_at')</th>
                            <th scope="col">@lang('a.id')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($values as $item)
                            <tr>
                                <td>{{ $item->log_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at->format(config('admin.date_format')) }}</td>
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
