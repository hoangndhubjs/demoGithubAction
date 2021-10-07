<div id="{{ $id }}">
    <input type='text' class="form-control" readonly  placeholder="{{ __("select_range_date") }}"/>
    <input type="hidden" name="{{ $name }}_start_date" id="{{ $name }}_start_date"/>
    <input type="hidden" name="{{ $name }}_end_date" id="{{ $name }}_end_date"/>
</div>

@push('stackJS')
<script>
    $(document).ready(function () {
        let options = {
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: window._dateFormat,
                cancelLabel: __('clear')
            }
        };
        @if($maxDate)
            let maxDate = moment('{{ $maxDate }}', "YYYY-MM-DD");
            options.maxDate = maxDate;
        @endif
        @if($minDate)
            let minDate = moment('{{ $minDate }}', "YYYY-MM-DD");
            options.minDate = minDate;
        @endif
        $('#{{ $id }}').daterangepicker(options, function(start, end) {
            $('#{{ $id }} .form-control').val( start.format(window._dateFormat) + ' {{ $delimiter }} ' + end.format(window._dateFormat));
            $('#{{ $id }} [name={{ $name }}_start_date]').val(start.format('YYYY-MM-DD'));
            $('#{{ $id }} [name={{ $name }}_end_date]').val(end.format('YYYY-MM-DD'));
        });
        $('#{{ $id }}').on('cancel.daterangepicker', function(e, picker) {
            $('#{{ $id }} .form-control').val('');
            $('#{{ $name }}_start_date').val('');
            $('#{{ $name }}_end_date').val('');

        });
    });
</script>
@endpush
