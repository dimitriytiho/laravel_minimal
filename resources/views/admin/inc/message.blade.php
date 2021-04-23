<div id="get-alert"></div>
{{--

Сообщения об ошибках --}}
@if(isset($errors) && $errors->any())
    <div class="mt-1">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul class="list-unstyled mb-0">
                @foreach($errors->all() as $error)
                    <li class="mt-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
{{--

Сообщения об успехе --}}
@if(session()->has('message') || session()->has('success'))
    <div class="mt-1">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            @if(session()->has('message'))
                <span>{{ session('message') }}</span>
            @endif
            @if(session()->has('message') && session()->has('success'))
                <br>
            @endif
            @if(session()->has('success'))
                <div>{{ session('success') }}</div>
            @endif
        </div>
    </div>
@endif
{{--

Сообщения информационные --}}
@if(session()->has('info'))
    <div class="mt-1">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <div>{{ session('info') }}</div>
        </div>
    </div>
@endif
