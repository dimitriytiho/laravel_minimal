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

                {!! $form::input('slug', $values->slug ?? null, true, null, true, null, null, [], null, null, null,
                    $form::inputGroupAppend('fas fa-sync', 'cur get_slug', 'bg-white', 'text-primary', ['data-url' => route('admin.get_slug'), 'data-src' => 'title', 'title' => __('a.generate_link')])) !!}

                {!! $form::textarea('description', $values->description ?? null, null) !!}

                {!! $form::textarea('body', $values->body ?? null, null, true, null, config('admin.editor'), null, 20) !!}

                @isset($values->id)
                    <div class="row">
                        <div class="col-md-4">
                            {!! $form::select('status', config('add.page_statuses'), $values->status ?? null) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_id">@lang('a.parent_id')</label>
                                <select class="form-control select2" name="parent_id" id="parent_id" aria-invalid="false">
                                    <option value="0">@lang('a.parent_id')</option>
                                    {!! Tree::get($all, 'admin_select', '-', $values->parent_id) !!}
                                </select>
                            </div>
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
                    @if(isset($values->slug) && Route::has($info['view']))
                        <a href="{{ route($info['view'], $values->slug) }}" class="btn btn-outline-info mt-3 pulse" target="_blank">@lang('s.go')</a>
                    @endif
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
