@if($isShowDate)
    <span class="text-muted font-weight-bold mr-2">{{ $date }} &copy;</span>
@else
    <a href="{{ url('/') }}" target="_blank" class="text-dark-75 text-hover-primary">{{ $text }}</a>
@endif
