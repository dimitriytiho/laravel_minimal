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

                {{ $form::input('title', [], $values->title ?? null) }}

                {{ $form::inputGroup('slug', [], $values->slug ?? null, true, true, null, $form::inputGroupAppend('fas fa-sync text-primary', 'cur get_slug', 'bg-white', ['data-url' => route('admin.get-slug'), 'data-src' => 'title', 'title' => __('a.generate_link')])) }}

                {{ $form::textarea('description', [], $values->description ?? null) }}

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
                            {{ $form::input('sort', ['type' => 'number', 'min' => '1', 'step' => '1', 'max' => '65535'], $values->sort ?? null) }}
                        </div>
                        {{--



                        Свойства --}}
                        @if(!empty($properties))
                            <div class="col-md-4">
                                {{ $form::select('properties[]', $properties, ['id' => 'properties', 'data-placeholder' => __('s.choose'), 'class' => 'w-100 select2', 'multiple' => 'multiple', 'values' => 'title'], $values->properties ?? null, false, 'properties', null, null, true) }}
                            </div>
                        @endif
                        {{--



                        Если есть связанные элементы, то выводим их в множественный select --}}
                        @if(!empty($relatedManyToManyEdit))
                            @foreach($relatedManyToManyEdit as $related)
                                @if(!empty($related[0]) && !empty($all[$related[0]]))
                                    <div class="col-md-6">
                                        {{ $form::select($related[0] . '[]', $all[$related[0]], ['id' => $related[0], 'data-placeholder' => __('s.choose'), 'class' => 'w-100 select2', 'multiple' => 'multiple', 'values' => $related[2] ?? null], $values->{$related[0]}, false, $related[0], null, null, true) }}
                                    </div>
                                @endif
                            @endforeach
                        @endif
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
                    @if(isset($values->slug) && Route::has($info->kebab))
                        <a href="{{ route($info->kebab, $values->slug) }}" class="btn btn-outline-info mt-3 pulse" target="_blank">@lang('s.go')</a>
                    @endif
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
