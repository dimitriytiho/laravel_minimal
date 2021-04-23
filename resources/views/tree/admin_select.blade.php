@if(!empty($viewName) && isset($item) && isset($id) && isset($i))
    <option
        value="{{ $id }}"
        {{ $id == $values ? 'selected' : null }}
        {{ $id == request()->segment(3) ? 'disabled' : null }}
    >
        {{ empty($tab) ? null : "{$tab} " }}
        {{ $item['title'] . " {$id}" }}
    </option>
    @isset($item['child'])
        {!! Tree::view($viewName, $item['child'], "{$tab}-") !!}
    @endisset
@endif
