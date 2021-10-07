{{-- Content --}}
@if (config('layout.content.extended'))
    @yield('content')
@else
    <div class="d-flex flex-column-fluid">
        <div class="{{ Metronic::printClasses('content-container', false) }}">
            @include('components.alert_box')
            @yield('content')
        </div>
    </div>
@endif
