@extends('layout.default')

@section('content')
@php
    $min_late = $min_late_soon[0]->min_late;
    $percent_to_late = ($min_late/300)*100;
    $min_soon = $min_late_soon[0]->min_soon;
    $percent_to_soon = ($min_soon/180)*100;

    $half_working = $attendance->half_working; //lam_nua_ngay
    $total_working = $attendance->full_working + $attendance->holiday - $attendance->ngay_nghi - ($attendance->nghi_nua_ngay/2); //ngay_cong + ngay_le - ngay_nghi - (lam_nua_ngay/2)
    $full_working = $total_working - ($half_working/2); //tong cong - lam_nua_ngay/2;
@endphp
    <div class="row">
        <div class="col-lg-12 col-xxl-12">
            <div class="card card-custom px-10 py-5 mb-5">
                <div class="info-covid-19">
                    <h1 class="text-dark mb-0 text-center font-size-h1-xl">Thông tin tổng hợp Covid-19</span> </h1>
                </div>
                <iframe src="https://emag.thanhnien.vn/widget/toan-canh-covid-21" frameborder="0" style="height: 190px; width: 100%; margin-left:0;"></iframe>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xxl-9">
            <!--begin::Mixed Widget 1-->
            <div class="card card-custom gutter-b px-10 py-5">
                <!--begin::Body-->
                <div class="card-body rounded p-0 d-flex justify-content-between align-items-center">
                    <div class="w-sm-500px w-lg-450px w-xl-600px w-xxl-700px">
                        <h1 class="text-dark mb-0">{{ $greeting }}, <a href="{{ route('profile')}}" class="text-primary">{{ Auth::user()->getFullName()}}</a> </h1>
                        <div class="font-size-h6 mt-5 text-muted" id="quote"></div>
                    </div>
                    <div class="d-none d-sm-block d-lg-block text-right w-350px ml-5">
                        <img src="{{ asset('media/covid-19.png') }}" alt="Cùng nhau chống lại kẻ hủy diệt" class="img-fluid">
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <div class="my-5 mx-0 card px-10 py-8">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="font-weight-bolder">{{ __('general_information') }} {{ __('month') }} {{ date('m')}} </h3>
                        <div class="row">
                            <div class="col-md-6 mt-5">
                                <div class="bg-light-primary w-100 px-6 py-5 rounded-xl">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M7.62302337,5.30262097 C8.08508802,5.000107 8.70490146,5.12944838 9.00741543,5.59151303 C9.3099294,6.05357769 9.18058801,6.67339112 8.71852336,6.97590509 C7.03468892,8.07831239 6,9.95030239 6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,9.99549229 17.0108275,8.15969002 15.3875704,7.04698597 C14.9320347,6.73472706 14.8158858,6.11230651 15.1281448,5.65677076 C15.4404037,5.20123501 16.0628242,5.08508618 16.51836,5.39734508 C18.6800181,6.87911023 20,9.32886071 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 C4,9.26852332 5.38056879,6.77075716 7.62302337,5.30262097 Z" fill="#000000" fill-rule="nonzero"/>
                                            <rect fill="#000000" opacity="0.3" x="11" y="3" width="2" height="10" rx="1"/>
                                        </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div class="w-50">
                                            <a href="">
                                                <p href="#" class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $day_off }}</p>
                                                <p href="#" class="text-dark-65 font-weight-bold font-size-h6 m-0">{{ __('number_of_days_off') }}</p>
                                            </a>
                                        </div>
                                        <div class="mt-xxl-8 mt-sm-12 w-50 text-right">
                                            <a href="{{ route('leaves.list') }}" class="btn btn-sm btn-primary font-weight-bold">{{ __('please_leave') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <div class="bg-light-primary px-6 py-5 rounded-xl">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"/>
                                        </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div class="w-50">
                                            <a href="">
                                                <p href="#" class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $order_meal }}</p>
                                                <p href="#" class="text-dark-65 font-weight-bold font-size-h6 m-0">{{ __('number_of_orders_rice') }}</p>
                                            </a>
                                        </div>
                                        <div class="mt-xxl-8 mt-sm-12 w-50 text-right">
                                            <a href="{{ route('orders.meal-order') }}" class="btn btn-sm btn-primary font-weight-bold">{{ __('left_order_rice') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <div class="
                                @if($min_late <= 200)
                                    bg-light-primary
                                @elseif($min_late > 200 && $min_late < 300)
                                    bg-light-warning
                                @elseif($min_late >= 300)
                                    bg-light-danger
                                @endif
                                px-6 py-5 rounded-xl">
                                    <span class="svg-icon svg-icon-3x
                                        @if($min_late <= 200)
                                            svg-icon-primary
                                        @elseif($min_late > 200 && $min_late < 300)
                                            svg-icon-warning
                                        @elseif($min_late >= 300)
                                            svg-icon-danger
                                        @endif
                                        d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M13,5.06189375 C12.6724058,5.02104333 12.3386603,5 12,5 C11.6613397,5 11.3275942,5.02104333 11,5.06189375 L11,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C14.5522847,2 15,2.44771525 15,3 C15,3.55228475 14.5522847,4 14,4 L13,4 L13,5.06189375 Z" fill="#000000"/>
                                            <path d="M16.7099142,6.53272645 L17.5355339,5.70710678 C17.9260582,5.31658249 18.5592232,5.31658249 18.9497475,5.70710678 C19.3402718,6.09763107 19.3402718,6.73079605 18.9497475,7.12132034 L18.1671361,7.90393167 C17.7407802,7.38854954 17.251061,6.92750259 16.7099142,6.53272645 Z" fill="#000000"/>
                                            <path d="M11.9630156,7.5 L12.0369844,7.5 C12.2982526,7.5 12.5154733,7.70115317 12.5355117,7.96165175 L12.9585886,13.4616518 C12.9797677,13.7369807 12.7737386,13.9773481 12.4984096,13.9985272 C12.4856504,13.9995087 12.4728582,14 12.4600614,14 L11.5399386,14 C11.2637963,14 11.0399386,13.7761424 11.0399386,13.5 C11.0399386,13.4872031 11.0404299,13.4744109 11.0414114,13.4616518 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>
                                        </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                                    <div>
                                        <a href="">
                                            <p href="#" class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $min_late ? $min_late : 0 }}/300</p>
                                            <p href="#" class="text-dark-65 font-weight-bold font-size-h6 m-0">{{ __('number_of_minutes_to_go_late') }}</p>
                                        </a>
                                    </div>
                                    <div class="progress progress-xs w-100 mt-3">
                                        <div class="progress-bar
                                        @if($min_late <= 200)
                                            bg-primary"  title="Good!"
                                        @elseif($min_late > 200 && $min_late < 300)
                                            bg-warning"  title="Average!"
                                        @elseif($min_late >= 300)
                                            bg-danger" title="Bad!"
                                        @endif
                                        role="progressbar" style="width: {{$percent_to_late}}%; cursor: pointer;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-theme="dark" data-placement="bottom"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <div class="
                                @if($min_soon <= 60)
                                    bg-light-primary
                                @elseif($min_soon > 60 && $min_soon < 180)
                                    bg-light-warning
                                @elseif($min_soon >= 180)
                                    bg-light-danger
                                @endif
                                px-6 py-5 rounded-xl">
                                    <span class="svg-icon svg-icon-3x
                                        @if($min_soon <= 60)
                                            svg-icon-primary
                                        @elseif($min_soon > 60 && $min_soon < 180)
                                            svg-icon-warning
                                        @elseif($min_soon >= 180)
                                            svg-icon-danger
                                        @endif
                                        d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M13,5.06189375 C12.6724058,5.02104333 12.3386603,5 12,5 C11.6613397,5 11.3275942,5.02104333 11,5.06189375 L11,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C14.5522847,2 15,2.44771525 15,3 C15,3.55228475 14.5522847,4 14,4 L13,4 L13,5.06189375 Z" fill="#000000"/>
                                            <path d="M16.7099142,6.53272645 L17.5355339,5.70710678 C17.9260582,5.31658249 18.5592232,5.31658249 18.9497475,5.70710678 C19.3402718,6.09763107 19.3402718,6.73079605 18.9497475,7.12132034 L18.1671361,7.90393167 C17.7407802,7.38854954 17.251061,6.92750259 16.7099142,6.53272645 Z" fill="#000000"/>
                                            <path d="M11.9630156,7.5 L12.0369844,7.5 C12.2982526,7.5 12.5154733,7.70115317 12.5355117,7.96165175 L12.9585886,13.4616518 C12.9797677,13.7369807 12.7737386,13.9773481 12.4984096,13.9985272 C12.4856504,13.9995087 12.4728582,14 12.4600614,14 L11.5399386,14 C11.2637963,14 11.0399386,13.7761424 11.0399386,13.5 C11.0399386,13.4872031 11.0404299,13.4744109 11.0414114,13.4616518 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>
                                        </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                                    <div>
                                        <a href="">
                                            <p href="#" class="text-dark font-weight-bold font-size-h2 mb-0 mt-1">{{ $min_soon ? $min_soon : 0 }}/180</p>
                                            <p href="#" class="text-dark-65 font-weight-bold font-size-h6 m-0">{{ __('number_of_minutes_to_leave_early') }}</p>
                                        </a>
                                    </div>
                                    <div class="progress progress-xs w-100 mt-3">
                                        <div class="progress-bar
                                        @if($min_soon <= 60)
                                            bg-primary"  title="Good!"
                                        @elseif($min_soon > 60 && $min_soon < 180)
                                            bg-warning"  title="Average!"
                                        @elseif($min_soon >= 180)
                                            bg-danger" title="Bad!"
                                        @endif
                                        role="progressbar" style="width: {{$percent_to_soon}}%; cursor: pointer;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-theme="dark" data-placement="bottom"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <!--begin::Mixed Widget 14-->
                        <div class="card card-custom shadow-none card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h5 class="font-weight-bolder mt-lg-15 mt-sm-15 mt-5">{{ __('attendance_chart') }} {{ __('month') }} {{ date('m') }}</h5>
                                <p class="text-muted">{{__('xin_yes') }} {{ $workday_to_do }} {{ __('attendance_low') }}</p>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <div class="flex-grow-1 mb-5">
                                    <div id="kt_mixed_widget_14_chart" style="height: 180px"></div>
                                </div>
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <b class="rounded text-center font-weight-bolder w-50px py-3 btn-primary">
                                            {{ $full_working ? $full_working : 0 }}
                                        </b>
                                        <b class="ml-2">{{__('attendance_full') }}</b>
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-center">
                                        <b class="rounded text-center font-weight-bolder w-50px py-3 btn-success">
                                            {{ $half_working ? $half_working : 0 }}
                                        </b>
                                        <b class="ml-2">{{__('attendance_half') }}</b>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Mixed Widget 14-->
                    </div>
                </div>
            </div>
            <!--end::Mixed Widget 1-->
        </div>

        <div class="col-lg-12 col-xxl-3">
            <div class="card card-custom gutter-b">
                <h3 class="font-weight-bolder text-center py-3">{{__('personal_calendar') }}</h3>
                <div class id="kt_datepicker_6"></div>
            </div>
            <div class="card card-custom gutter-b">
                <!--begin::Header-->
                <div class="card-header align-items-center border-0 mt-4">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="font-weight-bolder">{{__('xin_hr_events') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('calendars.list')}}" class="btn btn-sm btn-primary">{{ __('add_calendar') }}</a>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-0 mx-auto" id="timelineEvent">
                    <!--begin::Timeline-->
                    <div class="timeline timeline-6 mt-3 px-3" id="showEvent">
                        {{-- get data in file resources/js/dashboard/ --}}
                    </div>
                    <!--end::Timeline-->
                </div>
                <!--end: Card Body-->
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .datepicker{
            width: 100%;
        }
        .datepicker.datepicker-inline {
            border: 0px;
        }
        .timeline.timeline-6:before{
            left: 105px;
        }
        .timeline.timeline-6 .timeline-item .timeline-label{
            width: 95px;
            text-align: right;
            padding-right: 15px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        var full_working = @json($full_working);
        var half_working = @json($half_working);
        var total_working = @json($total_working);
        var workday_remaining = @json($workday_to_do-$total_working);
        var auth_id = @json(auth()->id());
    </script>

    <script src="{{ mix('js/dashboard/dashboard.js') }}"></script>
    <script src="{{ mix('js/amCharts/core.js') }}"></script>
    <script src="{{ mix('js/amCharts/charts.js') }}"></script>
    <script src="{{ mix('js/amCharts/animated.js') }}"></script>
@endsection
