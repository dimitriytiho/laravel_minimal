@foreach($values as $key => $value)
    <a href="{{ route($value->route, $value->slug) }}" class="media border-bottom py-3">
        {{--<img src="{{ asset($value->img) }}" class="img_mini" alt="{{ $value->title }}">--}}
        <div class="media-body">{{ $value->title }}</div>
    </a>
@endforeach
