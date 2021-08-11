@isset($queryArr)
    <div class="col-md-2 col-sm-3 mb-2">
        {{ $form::select('col', $queryArr, [
            'label' => 'false',
            'class' => 'custom-select custom-select-sm',
        ], $col ?? null) }}
    </div>
@endisset

<div class="col-md-3 col-sm-4 col-11 mt-2 mb-2">
    <div class="input-group input-group-sm">
        <input type="text" class="form-control" name="cell" id="cell" placeholder="@lang('a.search')..." value="{{ $cell ?? null }}">
        <label for="cell" class="sr-only"></label>
        <div class="input-group-append">
            <button type="submit" class="btn btn-default">
                <i class="fas fa-search" title="@lang('a.search')"></i>
            </button>
        </div>
    </div>
</div>

@isset($cell)
    <div class="col-1 mt-2 mb-2">
        <a href="{{ route("admin.{$info['kebab']}.index") }}" class="btn btn-link btn-sm px-0 pulse">
            <i class="fas fa-times" title="@lang('s.reset')"></i>
        </a>
    </div>
@endisset
