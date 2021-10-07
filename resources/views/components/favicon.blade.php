@if ($image)
    <link rel="icon" href="{{ url('/uploads/logo/favicon/'.$image) }}" sizes="16x16 32x32" type="image/png">
@else
    <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}"/>
@endif
