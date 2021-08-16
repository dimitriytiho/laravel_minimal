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

                {{ $form::input('key', [$disabledDelete ?? null], $values->key ?? null) }}

                @if(isset($values->type) && $values->type === (config('admin.setting_type')[1] ?? 'checkbox'))
                    {{ $form::toggle('value', [], $values->value ?? null, null, false) }}
                @else
                    {{ $form::textarea('value', [], $values->value ?? null) }}
                @endif

                <div class="row">
                    <div class="col-md-6">
                        {{ $form::select('type', config('admin.setting_type'), [], $values->type ?? null) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::select('section', config('admin.setting_section'), [], $values->section ?? null) }}
                    </div>
                </div>
                {{--



                Json data --}}
                {!! $form::jsonData('data', $values ?? null) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-4">
                            {{ $form::input('id', ['disabled'], $values->id, false) }}
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
                        <button type="submit" class="btn btn-primary mt-3 mr-2 pulse">{{ isset($values->id) ? __('s.save') : __('s.submit') }}</button>
                    </span>
                    {{--@if(isset($values->slug) && Route::has($info->kebab))
                        <a href="{{ route($info->kebab, $values->slug) }}" class="btn btn-outline-info mt-3 pulse" target="_blank">@lang('s.go')</a>
                    @endif--}}
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
    {{--


    Last data --}}
    @include('admin.inc.last_data')
@endsection
