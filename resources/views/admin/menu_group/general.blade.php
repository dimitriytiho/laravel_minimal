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

                {!! $form::input('title', $values->title ?? null) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-12">
                            {!! $form::input('sort', $values->sort ?? null) !!}
                        </div>
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
            @if(isset($values->{$belongTable}) && $values->{$belongTable}->count())
                <div class="text-right">
                    <div class="small text-secondary">@lang('s.remove_not_possible'),<br>@lang('s.there_are_nested') {{ Func::__($belongTable, 'a') }}</div>
                    @foreach($values->{$belongTable} as $item)
                        <a href="{{ route("admin.{$belongRoute}.edit", $item->id) }}">{{ $item->id }}</a>
                    @endforeach
                </div>
            @endif
            {{--


            Кнопка удалить --}}
            @if(
                isset($values->id)
                && !(isset($values->{$belongTable}) && $values->{$belongTable}->count())
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
