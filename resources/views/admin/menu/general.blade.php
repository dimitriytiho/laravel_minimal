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

        @include('admin.inc.belong_check')

        <div class="card-body">
            <form action="{{ isset($values->id) ? route("admin.{$info['slug']}.update", $values->id) : route("admin.{$info['slug']}.store") }}" method="post" class="validate" enctype="multipart/form-data" novalidate>
                @isset($values->id)
                    @method('put')
                @endisset
                @csrf

                {{ html()->hidden('belong_id', $values->belong_id ?? $currentParent->id ?? null) }}

                {{ $form::input('title', [], $values->title ?? null) }}

                {{ $form::inputGroup('slug', [], $values->slug ?? null, false, true, null, $form::inputGroupAppend('fas fa-sync text-primary', 'cur get_slug', 'bg-white', ['data-url' => route('admin.get_slug'), 'data-src' => 'title', 'title' => __('a.generate_link')])) }}

                <div class="row">
                    <div class="col-md-6">
                        {{ $form::input('item', [], $values->item ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('class', [], $values->class ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('target', [], $values->target ?? null, false) }}
                    </div>
                    <div class="col-md-6">
                        {{ $form::input('attrs', [], $values->attrs ?? null, false) }}
                    </div>
                </div>

                {{ $form::textarea('body', ['class' => config('admin.editor'), 'rows' => 20], $values->body ?? null) }}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-4">
                            {{ $form::select('status', config('add.statuses'), [], $values->status ?? null) }}
                        </div>
                        <div class="col-md-4">
                            @include('admin.tree.select_parent_id', compact('tree', 'values'))
                        </div>
                        <div class="col-md-4">
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
            @if(isset($values->id) && empty($deleteNo))
                <form action="{{ route("admin.{$info['slug']}.destroy", $values->id) }}" method="post" class="text-right confirm_form">
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

            // Удаляем неподходящии правила валидации
            $('#slug').rules('remove')

        }, false)
    </script>
@endpush
