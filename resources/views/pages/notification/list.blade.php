@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->
        <div class="flex-row-auto offcanvas-mobile w-200px w-xxl-275px" id="kt_inbox_aside">
            <!--begin::Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body px-5">
                    <!--begin::Compose-->
                    <div class="px-4 mt-4 mb-10">
                        <span class="font-weight-bold text-uppercase text-center">{{__('Loại thông báo')}}</span>
                    </div>
                    <!--end::Compose-->
                    <!--begin::Navigations-->
                    <div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
                        <!--begin::Item-->
                        <div class="navi-item my-2">
                            <a href="#" class="navi-link filter-item" data-filter-type="leave">
                                <span class="navi-icon mr-4">
                                    <span class="svg-icon svg-icon-lg">
                                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Electric/Shutdown.svg') }}
                                    </span>
                                </span>
                                <span class="navi-text {{--font-weight-bolder--}} font-size-lg">{{__('xin_employee_exit')}}</span>
                                {{--<span class="navi-label">
                                    <span class="label label-rounded label-light-success font-weight-bolder">10</span>
                                </span>--}}
                            </a>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="navi-item my-2">
                            <a href="#" class="navi-link filter-item" data-filter-type="request_ot">
                                <span class="navi-icon mr-4">
                                    <span class="svg-icon svg-icon-lg">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/Half-star.svg-->
                                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Home/Timer.svg') }}
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text {{--font-weight-bolder--}} font-size-lg">{{__('Làm thêm giờ')}}</span>
                            </a>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="navi-item my-2">
                            <a href="#" class="navi-link filter-item" data-filter-type="meetings">
                                <span class="navi-icon mr-4">
                                    <span class="svg-icon svg-icon-lg">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/Half-star.svg-->
                                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Communication/Dial-numbers.svg') }}
                                    </span>
                                </span>
                                <span class="navi-text {{--font-weight-bolder--}} font-size-lg">{{__('Họp')}}</span>
                            </a>
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Navigations-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Aside-->
        <!--begin::List-->
        <div class="flex-row-fluid ml-lg-8 d-block" id="kt_inbox_list">
            <!--begin::Card-->
            <div class="card card-custom card-stretch">
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body table-responsive">
                    <div class="card-title">
                        <h3 class="card-label" style="font-size: 1.3rem">{{__('Danh sách thông báo')}}
                            <span class="d-block text-muted pt-2 font-size-sm">{{__('Tất cả thông báo của bạn')}}</span></h3>
                    </div>
                    <div class="row col-12 col-sm-6 col-xxl-4 order-2 order-xxl-1 d-flex flex-wrap align-items-center">
                        <div class="d-flex align-items-center mr-1 my-2">
                            <label data-inbox="group-select" class="checkbox checkbox-inline checkbox-primary mr-3">
                                <input type="checkbox" id="selectAll">
                                <span class="symbol-label"></span>
                            </label>
                            <button type="button" class="btn btn-primary btn-sm ml-10 mr-2" id="readAll" style="width: 120px">{{__('Đánh dấu đã đọc')}}</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" style="width: 105px" id="is_notify_inactive">{{__('Thông báo mới')}}</button>
                        </div>
                    </div>
                    <!--begin::Items-->
                    <form id="filter-form">
                        <input type="hidden" name="type" value="" id="filter-type"/>
                        <input type="hidden" name="is_notify" value="" id="filter-is_notify"/>
                    </form>
                    <div class="datatable datatable-bordered datatable-head-custom" id="notification_list"></div>
                    {{--@foreach($notifications as $notification)
                    <div class="list list-hover min-w-500px" data-inbox="list">
                        <!--begin::Item-->
                        <div class="d-flex align-items-start list-item card-spacer-x py-3" data-inbox="message">
                            <!--begin::Toolbar-->
                            <div class="d-flex align-items-center">
                                <!--begin::Actions-->
                                <!--end::Actions-->
                                <!--begin::Author-->
                                <div class="d-flex align-items-center flex-wrap mr-3" data-toggle="view">
                                    <div class="symbol symbol-40 symbol-light-info mr-5">
                                        <span class="symbol-label">
                                            {{ Metronic::getSVG("media/svg/icons/Communication/Shield-user.svg", "svg-icon-lg  svg-icon-info") }}
                                        </span>
                                    </div>
                                    <a href="#" class="font-weight-bold text-dark-75 text-hover-primary">@if($notification->module_name == 'leave') {{__('xin_employee_exit')}} @elseif($notification->module_name == 'request_ot') {{__('Request OT')}} @endif</a>
                                </div>
                                <!--end::Author-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::Info-->
                            <div class="flex-grow-1 mt-2 mr-2" data-toggle="view">
                                <div>
                                    @if($notification->module_name == 'meetings')
                                        <a href="#" data-toggle='modal' data-target='#meeting_notification' data-id='{{$notification->module_id}}'
                                           class="@if($notification->is_notify == 1){{'text-dark'}} @else {{'text-muted'}} @endif text-hover-primary mb-1 font-size-lg">{{$notification->title}}</a>
                                    @else
                                    <a href="@if($notification->module_name == 'leave'){{route('leaves.detail', ['id'=>$notification->module_id])}}@elseif($notification->module_name == 'request_ot') {{route('overtime_request.detail', ['id'=>$notification->module_id])}} @else {{route('meeting.detail', ['id'=>$notification->module_id])}} @endif"
                                       class="@if($notification->is_notify == 1){{'text-dark'}} @else {{'text-muted'}} @endif text-hover-primary font-weight-bolder font-size-lg mr-2">{{$notification->title}}</a>
                                    <span class="text-muted">Thank you for ordering UFC 240 Holloway vs Edgar Alternate camera angles...</span>
                                    @endif
                                </div>
                            </div>
                            <!--end::Info-->
                            <!--begin::Datetime-->
                            <div class="mt-2 mr-3 font-weight-bolder text-right" data-toggle="view">{{$notification->created_at?date('d-m-Y', strtotime($notification->created_at)):''}}</div>
                            <!--end::Datetime-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Items-->
                    @endforeach--}}
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::List-->
    </div>
    <!--end::Modal-->
@endsection

@section('scripts')
    <script>
        function htmlDecode(input) {
            let doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
        window._tables = {
            'notification_list': {
                'url': '{{ route('notification.ajax.lists') }}',
                'columns': [
                    {
                        field: 'notificaion_id',
                        title: '',
                        width: 20,
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            return '<label class="checkbox checkbox-inline checkbox-primary flex-shrink-0 mr-3">\n' +
                                '<input type="checkbox" class="checkboxNotifi" name="notification_id" value="'+row.notificaion_id+'">\n' +
                                '<span></span>\n' +
                                '</label>';
                        }
                    },
                    {
                        field: 'icon',
                        title: '',
                        width: 30,
                        autoHide: false,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                'leave': {'data': '<span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                        '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                        '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                        '        <path d="M7.62302337,5.30262097 C8.08508802,5.000107 8.70490146,5.12944838 9.00741543,5.59151303 C9.3099294,6.05357769 9.18058801,6.67339112 8.71852336,6.97590509 C7.03468892,8.07831239 6,9.95030239 6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,9.99549229 17.0108275,8.15969002 15.3875704,7.04698597 C14.9320347,6.73472706 14.8158858,6.11230651 15.1281448,5.65677076 C15.4404037,5.20123501 16.0628242,5.08508618 16.51836,5.39734508 C18.6800181,6.87911023 20,9.32886071 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 C4,9.26852332 5.38056879,6.77075716 7.62302337,5.30262097 Z" fill="#000000" fill-rule="nonzero"/>\n' +
                                        '        <rect fill="#000000" opacity="0.3" x="11" y="3" width="2" height="10" rx="1"/>\n' +
                                        '    </g>\n' +
                                        '</svg></span>'},
                                'meetings': {'data': '<span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                        '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                        '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                        '        <rect fill="#000000" opacity="0.3" x="4" y="4" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="4" y="10" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="10" y="4" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="10" y="10" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="16" y="4" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="16" y="10" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="4" y="16" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="10" y="16" width="4" height="4" rx="2"/>\n' +
                                        '        <rect fill="#000000" x="16" y="16" width="4" height="4" rx="2"/>\n' +
                                        '    </g>\n' +
                                        '</svg></span>'},
                                'request_ot': {'data': '<span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                        '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                        '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                        '        <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z" fill="#000000" opacity="0.3"/>\n' +
                                        '        <path d="M13,5.06189375 C12.6724058,5.02104333 12.3386603,5 12,5 C11.6613397,5 11.3275942,5.02104333 11,5.06189375 L11,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C14.5522847,2 15,2.44771525 15,3 C15,3.55228475 14.5522847,4 14,4 L13,4 L13,5.06189375 Z" fill="#000000"/>\n' +
                                        '        <path d="M16.7099142,6.53272645 L17.5355339,5.70710678 C17.9260582,5.31658249 18.5592232,5.31658249 18.9497475,5.70710678 C19.3402718,6.09763107 19.3402718,6.73079605 18.9497475,7.12132034 L18.1671361,7.90393167 C17.7407802,7.38854954 17.251061,6.92750259 16.7099142,6.53272645 Z" fill="#000000"/>\n' +
                                        '        <path d="M11.9630156,7.5 L12.0369844,7.5 C12.2982526,7.5 12.5154733,7.70115317 12.5355117,7.96165175 L12.9585886,13.4616518 C12.9797677,13.7369807 12.7737386,13.9773481 12.4984096,13.9985272 C12.4856504,13.9995087 12.4728582,14 12.4600614,14 L11.5399386,14 C11.2637963,14 11.0399386,13.7761424 11.0399386,13.5 C11.0399386,13.4872031 11.0404299,13.4744109 11.0414114,13.4616518 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>\n' +
                                        '    </g>\n' +
                                        '</svg></span>'},
                                'default': {'data': '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Code\\Time-schedule.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                        '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                        '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                        '        <path d="M10.9630156,7.5 L11.0475062,7.5 C11.3043819,7.5 11.5194647,7.69464724 11.5450248,7.95024814 L12,12.5 L15.2480695,14.3560397 C15.403857,14.4450611 15.5,14.6107328 15.5,14.7901613 L15.5,15 C15.5,15.2109164 15.3290185,15.3818979 15.1181021,15.3818979 C15.0841582,15.3818979 15.0503659,15.3773725 15.0176181,15.3684413 L10.3986612,14.1087258 C10.1672824,14.0456225 10.0132986,13.8271186 10.0316926,13.5879956 L10.4644883,7.96165175 C10.4845267,7.70115317 10.7017474,7.5 10.9630156,7.5 Z" fill="#000000"/>\n' +
                                        '        <path d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z" fill="#000000" opacity="0.3"/>\n' +
                                        '    </g>\n' +
                                        '</svg></span>'},
                            };
                            return (status[row.module_name])?status[row.module_name].data:status['default'].data;
                        }
                    },
                    {
                        field: 'title',
                        title: '',
                        sortable: false,
                        autoHide: false,
                        template: function (row) {
                            let status = {
                                '1': {'class': ' font-weight-bolder font-size-lg mr-2'},
                                '0': {'class': ' text-muted'},
                            };
                        /*<img src="assets/media/svg/icons/Communication/Dial-numbers.svg"/>*/
                        /*<img src="assets/media/svg/icons/Home/Timer.svg"/>*/
                        /*<img src="assets/media/svg/icons/Electric/Shutdown.svg"/>*/
                        /*<img src="assets/media/svg/icons/Code/Warning-1-circle.svg"/>*/
                            return '<span class="' + status[row.is_notify].class + '">' + row.title + '</span>';
                        }
                    },
                    {
                        field: 'created_at',
                        sortable: false,
                        /*autoHide: false,*/
                        textAlign: 'right',
                        template: function (row) {
                            let html = '<span>' + moment(row.created_at).format("HH:mm") + '</span></br><span>' + moment(row.created_at).format("DD/MM/YYYY") + '</span>';
                            return html;
                        }
                    }
                ],
                'search_from': '#filter-form'
            }
        };
        $('#notification_list').on('click', 'td', function(e){
            var rowIndex = $(this).closest('tr').data('row');
            if ($(this).is(":first-child")) {
                if($(".checkboxNotifi").length == $(".checkboxNotifi:checked").length) {
                    $("#selectAll").prop("checked", true);
                } else {
                    $("#selectAll").prop("checked", false);
                }
            } else {
                // xử lý click
                let data = window._tables.notification_list.datatable.getDataSet()[rowIndex];
                if(data && data.module_name != 'meetings'){
                    window.location.href = data.url;
                } else {
                    $("#id-meeting").val(data.module_id);
                    $('#meeting_notification').modal('show');
                }
            }
        });

        $("#selectAll").click(function () {
            let checkAll = $("#selectAll").prop('checked');
            if (checkAll) {
                $(".checkboxNotifi").prop("checked", true);
            } else {
                $(".checkboxNotifi").prop("checked", false);
            }
        });

        $("#readAll").click(function () {
            let ids = $("#notification_list input:checkbox:checked").map(function () {
                return $(this).attr("value");
            }).get();

            if(ids.length > 0){
                $.ajax({
                    url: '{{ route('notification.ajax.update')}}',
                    data: {ids:ids},
                    method: "POST",
                    dataType: "json",
                }).done(function (response) {
                    if (response.success) {
                        $("#selectAll").prop("checked", false);
                        toastr.success(response.data);
                        window._tables.notification_list.datatable.reload();
                    } else {
                        toastr.error(response.data);
                    }
                }).fail(function (jqXHR, status) {
                    let statusCode = jqXHR.status;
                    if (statusCode !== 422) {
                        let errorText = {
                            "parsererror": __("Dữ liệu nhận được không đúng định dạng!"),
                            "error": (jqXHR.responseJSON.errors)?__(jqXHR.responseJSON.errors):__('Lỗi! Vui lòng thử lại'),
                            "timeout": __("Không có phản hồi từ máy chủ!"),
                            "abort": __("Yêu cầu bị hủy!")
                        };
                        toastr.error(errorText[status]);
                    } else {
                        let data = jqXHR.responseJSON?jqXHR.responseJSON:__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng");
                        let html = "";
                        if (typeof jqXHR.responseJSON.errors === "object") {
                            $.each(jqXHR.responseJSON.errors, function(fieldName, errors) {
                                html += errors[0];
                                return false;
                            });
                        }
                        toastr.error(html);
                    }
                });
            }
            return false;
        });
        $('.filter-item').click(function (e) {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('#filter-type').val("");
            } else {
                $('.filter-item').removeClass('active');
                $(this).addClass('active');
                var type = $(this).data('filter-type');
                $('#filter-type').val(type);
            }
            window._tables.notification_list.datatable.reload();
        });
        $('#is_notify_inactive').click(function (e) {
            $('#filter-is_notify').val(1);
            window._tables.notification_list.datatable.reload();
        });
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
