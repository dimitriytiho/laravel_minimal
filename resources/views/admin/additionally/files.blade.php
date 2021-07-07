@extends('layouts.admin')
{{--

Вывод контента

--}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div style="height: 600px;">
                <div id="fm"></div>
            </div>
        </div>
    </div>
@endsection
{{--


Этот код будет выведен в head --}}
@push('css')
    {{ html()->element('link')->attribute('rel', 'stylesheet')->attribute('href', asset('vendor/file-manager/css/file-manager.css')) }}
@endpush
