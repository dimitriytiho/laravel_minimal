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

                {!! $form::hidden('belong_id', $values->belong_id ?? $currentParent->id ?? null) !!}

                {!! $form::input('title', $values->title ?? null) !!}

                {!! $form::input('slug', $values->slug ?? null, null) !!}

                <div class="row">
                    <div class="col-md-6">
                        {!! $form::input('item', $values->item ?? null, null) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('class', $values->class ?? null, null) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('target', $values->target ?? null, null) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('attrs', $values->attrs ?? null, null) !!}
                    </div>
                </div>

                {!! $form::textarea('body', $values->body ?? null, null, true, null, config('admin.editor'), null, 20) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-4">
                            {!! $form::select('status', config('add.page_statuses'), $values->status ?? null) !!}
                        </div>
                        <div class="col-md-4">
                            @include('admin.tree.select_parent_id', compact('tree', 'values'))
                        </div>
                        <div class="col-md-4">
                            {!! $form::input('sort', $values->sort ?? null, null) !!}
                        </div>
                    </div>

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
            {{--


            Связь parent_id, если есть вложенные, то нельзя удалить --}}
            @if(isset($values->{$info['table']}) && $values->{$info['table']}->count())
                <div class="text-right">
                    <div class="small text-secondary">@lang('s.remove_not_possible'),<br>@lang('s.there_are_nested') {{ Func::__($info['table'], 'a') }}</div>
                    @foreach($values->{$info['table']} as $item)
                        <a href="{{ route("admin.{$info['slug']}.edit", $item->id) }}">{{ $item->id }}</a>
                    @endforeach
                </div>
            @endif
            {{--


            Кнопка удалить --}}
            @if(
                isset($values->id)
                && !(isset($values->{$info['table']}) && $values->{$info['table']}->count())
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
{{--


Этот код будет выведен после всех скриптов --}}
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Удаляем неподходящии правила валидации
            $('#slug').rules('remove')

        }, false)
    </script>
@endsection
