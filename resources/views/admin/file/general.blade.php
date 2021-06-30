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
            <form action="{{ isset($values->id) ? null : route("admin.{$info['slug']}.store") }}" method="post" class="validate" enctype="multipart/form-data" novalidate>
                @isset($values->id)
                    @method('put')
                @endisset
                @csrf

                @empty($values->id)
                    <div class="row">
                        @if($exts = config('admin.images_ext'))
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ext">@lang('a.max_size')</label>
                                    <select class="form-control" name="ext" id="ext">
                                        @foreach($exts as $ket => $ext)
                                            @php

                                                if (empty($ext[0])) {
                                                    $extTitle = ($ext[1] ?? null) . 'x' . ($ext[2] ?? null) . ' ' . Func::__($ext[3] ?? null, 'a');
                                                } else {
                                                    $extTitle = __('a.' . $ext[0]);
                                                }

                                            @endphp
                                            <option value="{{ $ket }}">{{ $extTitle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            {{ $form::toggle('webp', ['data-on-text' => 'save', 'data-off-text' => 'no_save'], false, null, false, __('a.webp')) }}
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="files">@lang('a.files')</label>
                        <div class="custom-file overflow-hidden">
                            <input type="file" class="custom-file-input" name="files[]" id="files" multiple>
                            <label class="custom-file-label" for="files">@lang('a.choose_file')</label>
                        </div>
                    </div>
                @endempty

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
                            {{ $form::input('path', ['disabled'], $values->path ?? null, false) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('old_name', ['disabled'], $values->old_name ?? null, false) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('size', ['disabled'], intval($values->size / 1000), false, __('a.size') . ' kb') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {{ $form::input('id', ['disabled'], $values->id ?? null, false) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('created_at', ['disabled'], $values->updated_at->format(config('admin.date_format')), false) }}
                        </div>
                    </div>
                @endisset
                @empty($values->id)
                    <div>
                        <span id="btn-sticky">
                            <button type="submit" class="btn btn-primary mt-3 mr-2 pulse">@lang('s.save')</button>
                        </span>
                    </div>
                @endempty
            </form>
            {{--


            Кнопка удалить --}}
            @if(
                isset($values->id)
                )
                <form action="{{ route("admin.{$info['slug']}.destroy", $values->id) }}" method="post" class="text-right confirm_form">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger mt-3 pulse">@lang('s.remove')</button>
                </form>
            @endif
        </div>
    </div>
@endsection
