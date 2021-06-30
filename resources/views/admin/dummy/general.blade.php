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

                {{ $form::input('title', [], $values->title ?? null) }}

                {{ $form::inputGroup('slug', [], $values->slug ?? null, false, true, null, $form::inputGroupAppend('fas fa-sync text-primary', 'cur get_slug', 'bg-white', ['data-url' => route('admin.get_slug'), 'data-src' => 'title', 'title' => __('a.generate_link')])) }}

                {{ $form::textarea('description', [], $values->description ?? null) }}

                {{ $form::textarea('body', ['class' => config('admin.editor'), 'rows' => 20], $values->body ?? null) }}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-6">
                            {{ $form::select('status', config('add.statuses'), [], $values->status ?? null) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('sort', [], $values->sort ?? null, false) }}
                        </div>
                    </div>

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
