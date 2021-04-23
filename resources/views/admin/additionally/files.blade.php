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
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection
