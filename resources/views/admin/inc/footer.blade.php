@php

    use App\Support\Admin\Html;


@endphp
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">PHP {{ phpversion() }} | Laravel {{ app()->version() }} | Admin LTE 3.0.3</div>
    <div>&copy; {{ date('Y') }} {{ config('add.dev') }}</div>
</footer>

<div class="btn btn-primary btn-sm scale-out pulse" id="btn_up" aria-label="@lang('s.move_to_top')" title="@lang('s.move_to_top')">
    <i class="fas fa-arrow-up"></i>
</div>

{!! Html::modal('confirm_modal', __('s.confirm')) !!}
    <h5 class="mt-3 mb-4">@lang('a.you_sure')</h5>
    <div class="text-right">
        <button type="button" class="btn btn-dark pulse mr-1" data-dismiss="modal">@lang('s.cancel')</button>
        <button type="button" class="btn btn-danger pulse" data-btn="ok">Ок</button>
    </div>
{!! Html::modalEnd() !!}
