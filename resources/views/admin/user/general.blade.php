@extends('layouts.admin')
{{--


Breadcrumbs --}}
@section('breadcrumbs')
    {{ Breadcrumbs::render('action') }}
@endsection
{{--


Вывод контента --}}
@section('content')
    <div class="row justify-content-center">
        @isset($values->id)
            <div class="col-md-4">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img_sm img-circle img_replace" src="{{ asset($values->file[0]->path ?? config('add.imgDefault')) }}" alt="{{ $values->name }}">
                            {{--

                            Удаление картинки --}}
                            @if(!empty($values->file[0]->path) && $values->file[0]->path !== config('add.imgDefault'))
                                <a href="{{ route(
                                        'admin.delete_file',
                                        [
                                            'token' => csrf_token(),
                                            'id' => $values->file[0]->id ?? 0,
                                        ]
                                        ) }}" class="text-danger p close confirm_link">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>

                        <h3 class="profile-username text-center mb-4">{{ $values->name }}</h3>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>@lang('a.user_id')</b> <span class="float-right">{{ $values->id }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('a.email')</b> <span class="float-right">{{ $values->email }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('a.ip')</b> <span class="float-right">{{ $values->ip }}</span>
                            </li>
                        </ul>
                        <div class="badge badge-{{ $values->status }} d-block mt-4 py-2">{{ Func::__($values->status, 'a') }}</div>
                    </div>
                </div>
                <!-- /.card Profile Image -->
            </div>
        @endisset

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($values->id) ? route("admin.{$info['slug']}.update", $values->id) : route("admin.{$info['slug']}.store") }}" method="post" class="validate" enctype="multipart/form-data" novalidate>
                        @isset($values->id)
                            @method('put')
                        @endisset
                        @csrf

                        {!! $form::textarea('note', $values->note ?? null, null) !!}

                        <div class="row">
                            <div class="col-md-6">
                                {!! $form::input('name', $values->name ?? null) !!}
                            </div>
                            <div class="col-md-6">
                                {!! $form::input('email', $values->email ?? null, true, 'email') !!}
                            </div>
                            {{--

                            Роли --}}
                            @if(!empty($roles))
                                <div class="col-md-6">
                                    {!! $form::select('roles', $roles, isset($values->id) ? $values->getRoleNames() : null, __('a.roles'), null, ['data-placeholder' => __('s.choose')], null, null, true, 'w-100 select2') !!}
                                </div>
                            @endif
                            {{--

                            Картинка --}}
                            @if(!empty($images))
                                <div class="col-md-6">
                                    {!! $form::select('file', $images, $values->file[0]->id ?? null, __('a.img'), null, ['data-placeholder' => __('s.choose')], true, null, null, 'w-100 select2_img') !!}
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {!! $form::input('password', null, null, 'password') !!}
                            </div>
                            <div class="col-md-6">
                                {!! $form::input('password_confirmation', null, null, 'password') !!}
                            </div>
                        </div>

                        @empty($values->id)
                            {!! $form::checkbox('accept', null, true, true, 'mb-4', __('s.accept'), 'yes', 'no') !!}
                        @endempty

                        @isset($values->id)
                            <div class="row">
                                <div class="col-md-4">
                                    {!! $form::input('id', $values->id, null, 'text', true, null, null, ['disabled' => 'true']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! $form::input('updated_at', $values->updated_at->format(config('admin.date_format')), null, 'text', true, null, null, ['disabled' => 'true']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! $form::input('created_at', $values->created_at->format(config('admin.date_format')), null, 'text', true, null, null, ['disabled' => 'true'])!!}
                                </div>
                            </div>
                        @endisset

                        <div>
                            <span id="btn-sticky">
                                <button type="submit" class="btn btn-primary mt-3 mr-2 pulse">{{ isset($values->id) ? __('s.save') : __('s.submit') }}</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
{{--


Этот код будет выведен после всех скриптов --}}
@section('scripts')
    @isset($values->id)
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Удаляем неподходящии правила валидации
                $('#password').rules('remove')
                $('#password_confirmation').rules('remove')

            }, false)
        </script>
    @endisset
@endsection
