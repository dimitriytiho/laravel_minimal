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
            <form action="{{ isset($values->id) ? route("admin.{$info['slug']}.update", $values->id) : route("admin.{$info['slug']}.store") }}" method="post" class="validate" enctype="multipart/form-data" novalidate>
                @isset($values->id)
                    @method('put')
                @endisset
                @csrf

                    {!! $form::input('key', $values->key ?? null, true, null, true, null, null, [$disabledDelete ?? null => null]) !!}

                    @if(isset($values->type) && $values->type === (config('admin.setting_type')[1] ?? 'checkbox'))
                        {!! $form::checkbox('value', $values->value ?? null) !!}
                    @else
                        {!! $form::textarea('value', $values->value ?? null, null) !!}
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            {!! $form::select('type', config('admin.setting_type'), $values->type ?? null) !!}
                        </div>
                        <div class="col-md-6">
                            {!! $form::input('section', $values->section ?? null, null) !!}
                        </div>
                    </div>

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
                    {{--@if(isset($values->slug) && Route::has($info['view']))
                        <a href="{{ route($info['view'], $values->slug) }}" class="btn btn-outline-info mt-3 pulse" target="_blank">@lang('s.go')</a>
                    @endif--}}
                </div>
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
