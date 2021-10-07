require('./bootstrap');
/**
 * Multiple language function
 */
window.__ = function(key, replace) {
    let translation;

    try {
        translation = key.split('.').reduce((t, i) => t[i] || null, window._translations);
    } catch (e) {
        translation = key;
    }

    if (!translation) return key;

    _.forEach(replace, (value, key) => {
        translation = translation.replace(':' + key, value);
    });

    return translation;
};

function __setupCurrency() {
    let settings = window._settings.default_currency.split(' - ');
    let code = settings[0];
    let symbol = settings[1];
    let currencyInfo = window._currencies[code];
    let options = {
        mark: currencyInfo.dec_point,
        thousand: currencyInfo.thousand_sep,
    };
    let connect_text = window._settings.show_currency === "symbol" ? symbol : code;
    if (window._settings.currency_position === "Suffix") {
        options.suffix = " "+connect_text;
    } else {
        options.prefix = connect_text+" ";
    }
    window._wNumb = wNumb(options);
}

window._userCurrency = function (money) {
    (!window._wNumb) && __setupCurrency();
    return window._wNumb.to(money);
};

window._currency2Number = function (formatted_money) {
    (!window._wNumb) && __setupCurrency();
    return window.wNumb.from(formatted_money);
}

window._userDate = function (date) {
    let DBFORMAT = 'YYYY-MM-DD HH:mm:ss';
    return moment(date, 'YYYY-MM-DD HH:mm:ss').format(window._dateFormat);
}

window._dbDateFormat = function (date, current_time = false) {
    let DBFORMAT = 'YYYY-MM-DD HH:mm:ss';
    let userFormat = window._dateFormat+' HH:mm:ss';
    if (!current_time) {
        date+=' 00:00:00';
    }
    return moment(date, userFormat).format(DBFORMAT);
}

window._loading = function(target, block = true) {
    $(target).find('.loading-backdrop').remove();
    if (!block) return;
    $(target).each(function (idx, item) {
        $(item).append('<div class="loading-backdrop d-flex align-items-center justify-content-center"><span class="spinner spinner-primary spinner-center"></span></div>');
    });
}

window._display_alert = function (target, message, icon = 'warning') {
    let html='<div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert"><div class="alert-icon">'
    html += '<i class="flaticon-'+icon+'"></i></div>';
    html += '<div class="alert-text">'+message+'</div>';
    html += '</div>';
    $(target).prepend(html);
}

window._display_alert_success = function (target, message, icon = 'exclamation-1') {
    let html='<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert"><div class="alert-icon">'
    html += '<i class="flaticon-'+icon+'"></i></div>';
    html += '<div class="alert-text">'+message+'</div>';
    html += '</div>';
    $(target).prepend(html);
}

window._userTime = function (datetime, onlytime = false) {
    let DBFORMAT = 'YYYY-MM-DD HH:mm:ss';
    return moment(datetime, DBFORMAT).format('HH:mm a');
    //Update for only time...
}

window._htmlEscapeEntities = function  ( d ) {
    return typeof d === 'string' ?
        d.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : d;
};

window.HRMSetting = function () {
    var get = function (key, defaultValue) {
        return window._settings[key] ?? defaultValue;
    };
    return {
        get: get
    };
};
// Setup select2;
$('[data-plugin-select2]').select2();
// Setup Ajax Header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Setup tranlsation for datatable
window.datatableTranslation = {
    "sProcessing":   __('sProcessing'),
    "sLengthMenu":   __('sLengthMenu'),
    "sZeroRecords":  __('sZeroRecords'),
    "sInfo":         __('sInfo'),
    "sInfoEmpty":    __('sInfoEmpty'),
    "sInfoFiltered": __('sInfoFiltered'),
    "sInfoPostFix":  "",
    "sSearch":       __('sSearch'),
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    __('sFirst'),
        "sPrevious": __('sPrevious'),
        "sNext":     __('sNext'),
        "sLast":     __('sLast')
    }
};

//icon in datatable
window.iconEdit = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Write.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 25 22" version="1.1">\n' +
            '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                '<rect x="0" y="0" width="24" height="24"/>\n' +
                '<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "/>\n' +
                '<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>\n' +
            '</g>\n' +
        '</svg><!--end::Svg Icon--></span>';
window.iconDelete = '<span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
            '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                '<rect x="0" y="0" width="24" height="24"/>\n' +
                '<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\n' +
                '<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\n' +
            '</g>\n' +
        '</svg><!--end::Svg Icon--></span>';
window.iconDoubleCheck = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Double-check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
    '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
    '        <polygon points="0 0 24 0 24 24 0 24"/>\n' +
    '        <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>\n' +
    '        <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>\n' +
    '    </g>\n' +
    '</svg><!--end::Svg Icon--></span>';
window.iconCheck = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
    '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
    '        <polygon points="0 0 24 0 24 24 0 24"/>\n' +
    '        <path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002) "/>\n' +
    '    </g>\n' +
    '</svg><!--end::Svg Icon--></span>';
window.iconClose = '<span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Close.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
    '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
    '        <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">\n' +
    '            <rect x="0" y="7" width="16" height="2" rx="1"/>\n' +
    '            <rect opacity="0.3" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) " x="0" y="7" width="16" height="2" rx="1"/>\n' +
    '        </g>\n' +
    '    </g>\n' +
    '</svg><!--end::Svg Icon--></span>';

window.iconExchange = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Exchange.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
    '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
    '        <polygon points="0 0 24 0 24 24 0 24"/>\n' +
    '        <rect fill="#000000" opacity="0.3" transform="translate(13.000000, 6.000000) rotate(-450.000000) translate(-13.000000, -6.000000) " x="12" y="8.8817842e-16" width="2" height="12" rx="1"/>\n' +
    '        <path d="M9.79289322,3.79289322 C10.1834175,3.40236893 10.8165825,3.40236893 11.2071068,3.79289322 C11.5976311,4.18341751 11.5976311,4.81658249 11.2071068,5.20710678 L8.20710678,8.20710678 C7.81658249,8.59763107 7.18341751,8.59763107 6.79289322,8.20710678 L3.79289322,5.20710678 C3.40236893,4.81658249 3.40236893,4.18341751 3.79289322,3.79289322 C4.18341751,3.40236893 4.81658249,3.40236893 5.20710678,3.79289322 L7.5,6.08578644 L9.79289322,3.79289322 Z" fill="#000000" fill-rule="nonzero" transform="translate(7.500000, 6.000000) rotate(-270.000000) translate(-7.500000, -6.000000) "/>\n' +
    '        <rect fill="#000000" opacity="0.3" transform="translate(11.000000, 18.000000) scale(1, -1) rotate(90.000000) translate(-11.000000, -18.000000) " x="10" y="12" width="2" height="12" rx="1"/>\n' +
    '        <path d="M18.7928932,15.7928932 C19.1834175,15.4023689 19.8165825,15.4023689 20.2071068,15.7928932 C20.5976311,16.1834175 20.5976311,16.8165825 20.2071068,17.2071068 L17.2071068,20.2071068 C16.8165825,20.5976311 16.1834175,20.5976311 15.7928932,20.2071068 L12.7928932,17.2071068 C12.4023689,16.8165825 12.4023689,16.1834175 12.7928932,15.7928932 C13.1834175,15.4023689 13.8165825,15.4023689 14.2071068,15.7928932 L16.5,18.0857864 L18.7928932,15.7928932 Z" fill="#000000" fill-rule="nonzero" transform="translate(16.500000, 18.000000) scale(1, -1) rotate(270.000000) translate(-16.500000, -18.000000) "/>\n' +
    '    </g>\n' +
    '</svg><!--end::Svg Icon--></span>';

window.iconRec = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Media\\Rec.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
    '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
    '        <rect x="0" y="0" width="24" height="24"/>\n' +
    '        <path d="M12,16 C14.209139,16 16,14.209139 16,12 C16,9.790861 14.209139,8 12,8 C9.790861,8 8,9.790861 8,12 C8,14.209139 9.790861,16 12,16 Z M12,20 C7.581722,20 4,16.418278 4,12 C4,7.581722 7.581722,4 12,4 C16.418278,4 20,7.581722 20,12 C20,16.418278 16.418278,20 12,20 Z" fill="#000000" fill-rule="nonzero"/>\n' +
    '    </g>\n' +
    '</svg><!--end::Svg Icon--></span>';

window.formart_number = function (element) {
    $(element).keyup(function() {
        var price = $(this).val();
        text  = price.split(/[a-zA-Z]/g).join("");
        string = text.replace(/_|-|\./gi, "").split(/(?=(?:\d{3})+$)/).join(".");
            // text.replace(/\./gi, "").split(/(?=(?:\d{3})+$)/).join(".");
        $(this).val(string);
    });
};
// custom select2
// $('#kt_datatable_search_status').select2();
// $('#kt_datatable_search_type').select2();
