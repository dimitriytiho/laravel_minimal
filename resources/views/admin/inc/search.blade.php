<div class="row">
    {{--

    Кнопка создать для текущего класса --}}
    @if(Route::has("admin.{$info['slug']}.create"))
        <div class="col-md-1">
            <a href="{{ route("admin.{$info['slug']}.create") }}" class="btn btn-info btn-sm d-block pulse">@lang('a.create')</a>
        </div>
    @endif
    @if(!empty($queryArr) && Route::has("admin.{$info['slug']}.index"))
        <div class="col-md-11">
            {{--

            Форма поиска --}}
            <form action="{{ route("admin.{$info['slug']}.index") }}" class="mb-1">
                <div class="row">
                    @isset($parentValues)
                        <div class="col-md-2 col-sm-3 mb-2">
                            <label for="parent_values" class="sr-only"></label>
                            <select class="custom-select custom-select-sm select_change" id="parent_values" data-url="{{ route('admin.get_cookie') }}" data-key="{{ $info['table'] }}_id">
                                @foreach($parentValues as $id => $title)
                                    <option value="{{ $id }}" @if(!empty($currentParent) && $currentParent->id == $id) selected @endif>{{ Func::__($title, 'a') }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset
                    @if($queryArr)
                        <div class="col-md-2 col-sm-3 mb-2">
                            <label for="col" class="sr-only"></label>
                            <select class="custom-select custom-select-sm" name="col" id="col">
                                @foreach($queryArr as $option)
                                    <option value="{{ $option }}" @if($col === $option) selected @endif>@lang("a.{$option}")</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-4 col-sm-6 col-11 mb-2">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="cell" id="cell" placeholder="@lang('a.search')..." value="@if(!empty($cell)){{ $cell }}@endif">
                            <label for="cell" class="sr-only"></label>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search" title="@lang('a.search')"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if($cell)
                        <div class="col-1 mb-2">
                            <a href="{{ route("admin.{$info['slug']}.index") }}" class="btn btn-link btn-sm px-0 pulse">
                                <i class="fas fa-times" title="@lang('s.reset')"></i>
                            </a>
                        </div>
                    @endif
                    @if(config('admin.pagination'))
                        <div class="col-sm-2 col-xl-1 ml-0 ml-md-auto">
                            <div class="dataTables_length">
                                <label>
                                    <select aria-controls="quantity_pagination" class="custom-select custom-select-sm select_change" data-url="{{ route('admin.pagination') }}">
                                        @foreach(config('admin.pagination') as $qty)
                                            <option value="{{ $qty }}" {{ $qty == session()->has('pagination') ? ($qty == session('pagination') ? 'selected' : null) : ($qty == config('admin.pagination_default') ? 'selected' : null) }}>{{ $qty }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    @endif
</div>
