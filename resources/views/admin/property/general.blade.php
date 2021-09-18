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

                <div class="row">
                    <div class="col-md-6">
                        {{ $form::input('title', [], $values->title ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('slug', [], $values->slug ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('number', ['type' => 'number', 'step' => '0.01', 'min' => '0'], $values->number ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('old', ['type' => 'number', 'step' => '0.01', 'min' => '0'], $values->old ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::select('type', App\Support\Admin\App::getModels(true), [], $values->type ?? null) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::toggle('default', [], $values->default ?? null) }}
                    </div>
                </div>

                {{ $form::textarea('description', [], $values->description ?? null) }}

                {{ $form::textarea('body', ['class' => config('admin.editor'), 'rows' => 20], $values->body ?? null) }}
                {{--



                Json data --}}
                {!! $form::jsonData('data', $values ?? null) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-6">
                            {{ $form::select('status', config('add.statuses'), [], $values->status ?? null) }}
                        </div>
                        <div class="col-md-6">
                            {{ $form::input('sort', ['type' => 'number', 'min' => '1', 'step' => '1', 'max' => '65535'], $values->sort ?? null) }}
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
                            {{ $form::input('created_at', ['disabled'], $values->created_at->format(config('admin.date_format')), false) }}
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



            Если есть связанные элементы не удалять --}}
            @if(isset($values) && !empty($relatedManyToManyDelete))
                @foreach($relatedManyToManyDelete as $related)
                    @if(!empty($related[0]) && !empty($related[1]) && isset($values->{$related[0]}) && $values->{$related[0]}->count())
                        @php

                            $deleteNo = true;

                        @endphp
                        <div class="text-right">
                            <div class="small text-secondary">@lang('s.remove_not_possible'),<br>@lang('s.there_are_nested') {{ Func::__($related[0], 'a') }}</div>
                            @if(Route::has("admin.{$related[1]}.edit"))
                                @foreach($values->{$related[0]} as $item)
                                    <a href="{{ route("admin.{$related[1]}.edit", $item->id) }}">{{ $item->id }}</a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif
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
{{--


Этот код будет выведен после всех скриптов --}}
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Удаляем неподходящие правила валидации
            $('#title').rules('remove')
            $('#slug').rules('remove')

        }, false)
    </script>
@endpush
