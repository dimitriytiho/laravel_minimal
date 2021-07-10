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
                            @if(!empty($values->file[0]) && !empty($values->file[0]->path) && $values->file[0]->path !== config('add.imgDefault'))
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

                        {{ $form::textarea('note', [], $values->note ?? null) }}

                        <div class="row">
                            <div class="col-md-6">
                                {{ $form::input('name', [], $values->name ?? null) }}
                            </div>
                            <div class="col-md-6">
                                {{ $form::input('email', ['type' => 'email'], $values->email ?? null) }}
                            </div>
                            {{--

                            Роли --}}
                            @if(!empty($roles))
                                <div class="col-md-6">
                                    {{ $form::select('roles[]', $roles, ['id' => 'roles', 'data-placeholder' => __('s.choose'), 'class' => 'w-100 select2', 'multiple' => 'multiple'], isset($values->id) ? $values->getRoleNames() : null, false, 'roles', null, null, true) }}
                                </div>
                            @endif
                            {{--

                            Разрешения. Если авторизированный пользователь не админ и редактируемый пользователь админ, то не показываем --}}
                            @if(!empty($permissions) && !(!auth()->user()->hasRole($adminRoleName) && isset($values->id) && $values->hasRole($adminRoleName)))
                                <div class="col-md-6">
                                    {{ $form::select('permissions[]', $permissions, ['id' => 'permissions', 'data-placeholder' => __('s.choose'), 'class' => 'w-100 select2', 'multiple' => 'multiple'], isset($values->id) ? $values->getPermissionNames() : null, false, 'permissions', null, null, true) }}
                                </div>
                            @endif
                            {{--



                            Если есть связанные элементы, то выводим их в множественный select --}}
                            {{--@if(!empty($relatedManyToManyEdit))
                                @foreach($relatedManyToManyEdit as $related)
                                    @if(!empty($related[0]) && !empty($all[$related[0]]))
                                        <div class="col-md-4">
                                            {{ $form::select($related[0] . '[]', $all[$related[0]], ['id' => $related[0], 'data-placeholder' => __('s.choose'), 'class' => 'w-100 select2', 'multiple' => 'multiple'], $values->{$related[0]} ?? null, false, $related[0], null, null, true) }}
                                        </div>
                                    @endif
                                @endforeach
                            @endif--}}
                            {{--

                            Картинка --}}
                            @if(!empty($images))
                                <div class="col-md-6">
                                    {{ $form::select('file', $images, ['data-placeholder' => __('s.choose'), 'class' => 'w-100 select2_img'], $values->file[0]->id ?? null, false, 'img', null, null, true) }}
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{ $form::input('password', ['type' => 'password'], null, false) }}
                            </div>
                            <div class="col-md-6">
                                {{ $form::input('password_confirmation', ['type' => 'password'], null, false) }}
                            </div>
                        </div>

                        @empty($values->id)
                                {{ $form::toggle('accept', ['data-on-text' => 'yes', 'data-off-text' => 'no'], false, null, false, __('s.accept')) }}
                        @endempty

                        @isset($values->id)
                            <div class="row">
                                <div class="col-md-4">
                                    {{ $form::input('id', ['disabled'], $values->id ?? null, false) }}
                                </div>
                                <div class="col-md-4">
                                    {{ $form::input('updated_at', ['disabled'], $values->updated_at->format(config('admin.date_format')), false) }}
                                </div>
                                <div class="col-md-4">
                                    {{ $form::input('created_at', ['disabled'], $values->updated_at->format(config('admin.date_format')), false) }}
                                </div>
                            </div>
                        @endisset

                        <div>
                            <span id="btn-sticky">
                                <button type="submit" class="btn btn-primary mt-3 mr-2 pulse">{{ isset($values->id) ? __('s.save') : __('s.submit') }}</button>
                            </span>
                        </div>
                    </form>
                    {{--



                    Кнопка удалить, удалить может только Admin --}}
                    @if(isset($values->id) && auth()->user()->hasRole(\App\Models\User::getRoleAdmin()))
                        <form action="{{ route("admin.{$info['slug']}.destroy", $values->id) }}" method="post" class="text-right confirm_form">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger mt-3 pulse">@lang('s.remove')</button>
                        </form>
                    @endif
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    {{--


    Last data --}}
    @include('admin.inc.last_data')
@endsection
{{--


Этот код будет выведен после всех скриптов --}}
@push('js')
    @isset($values->id)
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Удаляем неподходящии правила валидации
                $('#password').rules('remove')
                $('#password_confirmation').rules('remove')

            }, false)
        </script>
    @endisset
@endpush
