@if(empty($noShowErrorPage) && auth()->check() && auth()->user()->hasRole(\App\Services\Auth\Role::ADMIN_PANEL_NAMES))
    <div class="panel-dashboard d-none d-lg-block">
        <a href="{{ session()->get('back_link_admin', route('admin.main')) }}" class="panel-dashboard__icons" title="@lang('a.dashboard')">
            <i class="fas fa-tachometer-alt"></i>
        </a>
        @if(isset($values->id) && !empty($info['kebab']) && Route::has("admin.{$info['kebab']}.edit"))
            <a href="{{ route("admin.{$info['kebab']}.edit", $values->id) }}" class="panel-dashboard__icons" target="_blank" title="@lang('a.edit')">
                <i class="fas fa-edit"></i>
            </a>
        @endif
    </div>
@endif
