@extends('layouts.admin')
{{--


Breadcrumbs --}}
@section('breadcrumbs')
    {{ Breadcrumbs::render('action') }}
@endsection
{{--


Вывод контента --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($values->id) ? route("admin.{$info->kebab}.update", $values->id) : route("admin.{$info->kebab}.store") }}" method="post" class="validate" enctype="multipart/form-data" novalidate>
                @isset($values->id)
                    @method('put')
                @endisset
                @csrf
                {{--

                Для создания элемента --}}
                @empty($values->id)
                    <div class="row">
                        @isset($exts)
                            <div class="col-md-4">
                                {{ $form::select('ext', $exts, [], null, false, 'max_size', null, null, true) }}
                            </div>
                        @endisset
                        <div class="col-md-4">
                            {{ $form::select('type', config('admin.file_models')/*App\Support\Admin\App::getModels(true, false)*/, [], $values->type ?? null) }}
                        </div>
                        {{--<div class="col-md-4">
                            {{ $form::toggle('webp', ['data-on-text' => 'save', 'data-off-text' => 'no_save'], true, null, false, __('a.webp')) }}
                        </div>--}}
                    </div>


                    <div class="form-group">
                        <label for="files">@lang('a.files')</label>
                        <div class="custom-file overflow-hidden">
                            <input type="file" class="custom-file-input" name="files[]" id="files" multiple>
                            <label class="custom-file-label" for="files">@lang('a.choose_file')</label>
                        </div>
                    </div>
                @endempty
                {{--

                Для редактирования элемента --}}
                @isset($values->id)
                    {{--


                    Картинка --}}
                    @if(in_array($values->ext, config('add.imgExt') ?: []))
                        <div class="row">
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col">
                                        <img src="{{ asset($values->path ?? config('add.imgDefault')) }}" class="img-thumbnail" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            {{ $form::input('name', ['disabled'], $values->name ?? null, false) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('old_name', [], $values->old_name ?? null, false) }}

                        </div>
                        <div class="col-md-6">
                            {{ $form::input('path', ['disabled'], $values->path ?? null, false) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('size', ['disabled'], intval($values->size / 1000), false, __('a.size') . ' kb') }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::select('type', config('admin.file_models')/*App\Support\Admin\App::getModels(true)*/, [], $values->type ?? null) }}
                        </div>
                        @isset($values->user_id)
                            <div class="col-md-6">
                                {{ $form::input('user', ['disabled'], $values->user->id . ' - ' . $values->user->name, false) }}
                            </div>
                        @endisset
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            {{ $form::input('id', ['disabled'], $values->id ?? null, false) }}
                        </div>
                        <div class="col-md-4">
                            {{ $form::input('updated_at', ['disabled'], $values->updated_at->format(config('admin.date_format')), false) }}
                        </div>
                        <div class="col-md-4">
                            {{ $form::input('created_at', ['disabled'], $values->created_at->format(config('admin.date_format')), false) }}
                        </div>
                    </div>
                @endisset
                <div>
                    <span id="btn-sticky">
                        <button type="submit" class="btn btn-primary mt-3 mr-2 pulse">@lang('s.save')</button>
                    </span>
                </div>
            </form>
            {{--


            Кнопка удалить --}}
            @if(
                isset($values->id)
                )
                <form action="{{ route("admin.{$info->kebab}.destroy", $values->id) }}" method="post" class="text-right confirm_form">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger mt-3 pulse">@lang('s.remove')</button>
                </form>
            @endif
        </div>
    </div>
@endsection
