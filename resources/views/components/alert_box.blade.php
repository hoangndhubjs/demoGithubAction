@if(session()->has('alert_errors'))
    @foreach(session('alert_errors') as $error)
        <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning text-danger"></i></div>
            <div class="alert-text">{{ $error }}</div>
        </div>
    @endforeach
@endif
@if(session()->has('alert_successes'))
    @foreach(session('alert_successes') as $success)
        <div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning text-success"></i></div>
            <div class="alert-text">{{ $success }}</div>
        </div>
    @endforeach
@endif
