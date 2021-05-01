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

                <div class="row">
                    <div class="col-md-6">
                        {!! $form::input('title', $values->title ?? null, null) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('slug', $values->slug ?? null, null) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('number', $values->number ?? null, null, 'number', true, null, null, ['step' => '0.01', 'min' => '0']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::input('old', $values->old ?? null, null, 'number', true, null, null, ['step' => '0.01', 'min' => '0']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::select('property_id', $all ?? null, $values->property_id ?? null, __('a.properties') . ' ' . Str::lower(__('a.parent')), null, ['data-placeholder' => __('s.choose')], true, null, null, 'w-100 select2') !!}
                    </div>
                    <div class="col-md-6">
                        {!! $form::checkbox('default', $values->default ?? null, null, null, 'mb-4') !!}
                    </div>
                </div>

                {!! $form::textarea('description', $values->description ?? null, null) !!}

                {!! $form::textarea('body', $values->body ?? null, null, true, null, config('admin.editor'), null, 20) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-6">
                            {!! $form::select('status', config('add.statuses'), $values->status ?? null) !!}
                        </div>
                        <div class="col-md-6">
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
{{--


Этот код будет выведен после всех скриптов --}}
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Удаляем неподходящии правила валидации
            $('#title').rules('remove')
            $('#slug').rules('remove')

        }, false)
    </script>
@endsection
