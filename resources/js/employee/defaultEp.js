$(document).ready(function() {

    $(':input').change(function () {
        $(this).closest('.form-group').find('.form-text.text-danger').hide();
    });

    $('.selectSearch').select2({ width: '100%' });

    $('.datepickerDefault').datepicker({
        todayHighlight: true,
        format: window._dateFormat.toLowerCase(),
        default: 'toDay',
        setDate: new Date(),
        orientation: "bottom left",
        language: window._locale,
        endDate: "toDay",
    });

    // input number
    $(".numberRq").on("keypress keyup blur", function(event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

});
