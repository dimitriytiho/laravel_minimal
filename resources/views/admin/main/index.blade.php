@extends('layouts.admin')

@section('content')
    <section class="row">
        {{--{!! $html::smallBox('success', 'far fa-comment-alt', $countTable['Form'] ?? '0', 'forms', 'admin.form.index') !!}--}}

        {!! $html::smallBox('warning', 'fas fa-columns', $countTable['pages'] ?? '0', 'pages', 'admin.page.index') !!}

        {!! $html::smallBox('danger', 'fas fa-user-friends', $countTable['users'] ?? '0', 'users', 'admin.user.index') !!}
    </section>

    <section class="card mt-3">
        <div class="card-body">
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="{{ asset($values->file[0]->path ?? config('add.imgDefault')) }}" alt="User image">
                <span class="username">
                  @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.user.edit', auth()->user()->id) }}">{{ auth()->user()->name }}</a>
                    @else
                        <span>{{ auth()->user()->name }}</span>
                    @endif
                </span>
                <span class="description">@lang('a.welcome')</span>
            </div>
        </div>
    </section>

    <div class="py-5"></div>
@endsection
