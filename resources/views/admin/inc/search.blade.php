<div class="row">
    {{--

    Кнопка создать для текущего класса --}}
    @if(Route::has("admin.{$info->kebab}.create"))
        <div class="col-md-1 mt-2">
            <a href="{{ route("admin.{$info->kebab}.create") }}" class="btn btn-info btn-sm d-block pulse">@lang('a.create')</a>
        </div>
    @endif
    @if(!empty($queryArr) && Route::has("admin.{$info->kebab}.index"))
        <div class="col-md-11">
            {{--

            Форма поиска --}}
            <form action="{{ route("admin.{$info->kebab}.index") }}" class="mb-1">
                <div class="row">
                    @isset($parentValues)
                        <div class="col-md-2 col-sm-3 mb-2">
                            {{ $form::select('parent_values', $parentValues, [
                                'label' => 'false',
                                'lang' => 'false',
                                'class' => 'custom-select custom-select-sm select_change',
                                'data-url' => route('admin.get-cookie'),
                                'data-key' => $info->table . '_id'
                            ], Cookie::get($info->table . '_id'), false, false, null, null, true) }}
                        </div>
                    @endisset

                    @include('admin.inc.search_form')

                    @include('admin.inc.search_pagination')

                </div>
            </form>
        </div>
    @endif
</div>
