@if(empty($noShowErrorPage) && auth()->check() && auth()->user()->hasRole('admin'))
    <div class="panel-dashboard d-none d-lg-block">
        <a href="{{ session()->get('back_link_admin', route('admin.main')) }}" class="panel-dashboard__icons" title="@lang('a.Dashboard')">
            <i class="fas fa-tachometer-alt"></i>
        </a>
        @if(!empty($values->id) && !empty($info['view']) && Route::has('admin.' . $info['view'] . '.edit'))
            <a href="{{ route('admin.' . $info['view'] . '.edit', $values->id) }}" class="panel-dashboard__icons" target="_blank" title="@lang('a.edit')">
                <i class="fas fa-edit"></i>
            </a>
        @endif
    </div>
@endif
