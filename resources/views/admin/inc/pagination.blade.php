@if(method_exists($values, 'links'))
    <div class="d-flex justify-content-between">
        <p class="font-weight-light text-secondary mt-4">{{ __('a.shown') . $values->count() . __('a.of') .  $values->total() }}</p>
        <div class="mt-3">{{ $values->withQueryString()->onEachSide(2)->links() }}</div>
    </div>
@endif
