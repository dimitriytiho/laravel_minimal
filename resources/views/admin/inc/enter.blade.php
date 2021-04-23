@extends('layouts.enter')
{{--

Вывод контента

--}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-6 bg-white rounded shadow p-5 enter">
                <h1 class="font-weight-light text-secondary pb-2">@lang('s.login')</h1>
                <form method="post" action="{{ route('enter_post') }}" class="form_post mt-4" name="enter" novalidate>
                    @csrf
                    {!! $html::input('email', null, true, 'email', null) !!}
                    {!! $html::input('password', null, true, 'password', null) !!}
                    {!! $html::checkbox('remember', null, true) !!}
                    {{--@if(empty($auth_view))
                        {!! $html::input('email', null, true, 'email', null) !!}
                    @elseif($auth_view == 'confirm')
                        {!! $html::input('confirm', null, true, 'text', null) !!}
                    @elseif($auth_view == 'password')
                        {!! $html::input('password', null, true, 'password', null) !!}
                        {!! $html::checkbox('remember', null, true) !!}
                    @endif--}}
                    <button type="submit" class="btn btn-primary mt-2 btn-pulse">
                        <span class="btn-spinner">
                            <span class="spinner-grow spinner-grow-sm mr-1" role="status" aria-hidden="true"></span>
                        </span>
                        <span>@lang('s.submit')</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
