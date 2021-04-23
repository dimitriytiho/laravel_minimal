<div id="get_alert_js"></div>
{{--

Сообщения об ошибках --}}
@if(isset($errors) && $errors->any())
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show py-3 px-4" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach($errors->all() as $error)
                            <li class="mt-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="@lang('s.Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
{{--

Сообщения об успехе --}}
@if(session()->has('message') || session()->has('success'))
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-success alert-dismissible fade show py-3 px-4" role="alert">
                    @if(session()->has('message'))
                        <span>{{ session('message') }}</span>
                    @endif
                    @if(session()->has('message') && session()->has('success'))
                        <br>
                    @endif
                    @if(session()->has('success'))
                        <span>{{ session('success') }}</span>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="@lang('s.Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
{{--

Сообщения информационные или статусов --}}
@if(session()->has('info') || session()->has('status'))
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-info alert-dismissible fade show py-3 px-4" role="alert">
                    @if(session()->has('info'))
                        <span>{{ session('info') }}</span>
                    @endif
                    @if(session()->has('info') && session()->has('status'))
                        <br>
                    @endif
                    @if(session()->has('status'))
                        <span>{{ session('status') }}</span>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="@lang('s.Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
