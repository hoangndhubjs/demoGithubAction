@extends('layout.default')
@section('styles')
    <style>
        /* set styles default */
        .f12{font-size: 12px}
        .f16{font-size: 16px}
        .f14{font-size: 14px}
        .f36{font-size: 36px}
        .f20{font-size: 20px}
        .f24{font-size: 24px}
        .reset-pm{margin: 0;padding: 0;}
        /* set styles default */
        .paycheck_salray{font-size:36px;padding:20px 0;}
        .salary_detail_banner{width:70%;margin:auto;padding:4rem 0 4rem 0;}
        .data_salary_month p{margin:0;}
        .salary_detail_table{width:70%;margin:auto}
        .list_field_data{margin:0 3rem;}
        /*0 2.5rem*/
        .list_field_data{margin:0;font-size:1.1em;line-height:3rem;display:none;}
        .footer_salary_user{background:#f7f7f7;}
        .footer_salary_user .salary_detail_table{padding:3rem 0;}
        .footer_salary_user .net_salary_user{text-align:right;line-height:1.5rem;display:flex;justify-content:flex-end;padding-top:5px;}
        .month_filter{position:relative;padding-left:4.5rem;font-size:1.2em;}
        .month__apend{position:absolute;top:50%;left:10%;transform:translate(0,-50%);font-size:1.1em;color:#ababab;}
        .table_{border-top:1px #e2e2e2 dashed;}
        .show_salary:hover{cursor:pointer;}
        .title_salary_detail{margin:0;}
        .pd_t{padding: 0.5rem 0 0.5rem 0;}
        .icons_dropdown .icons_down {
            font-size: 1rem;
            padding-left: 0.5rem;
        }
        @media (max-width:600px){.mobile-fix{display:none;}
            .content_salary_body{padding:5px 10px;}
            .container-fluid{padding:0}
            .container_mobile{padding:0;}
            .filter_mobile{margin:0;}
            .filter_mobile .col-filter-left{width:60%;padding:0;}
            .filter_mobile .col-filter-right{width:30%;padding:0;}
            .filter_mobile .row-mobile{padding:0;margin:0;justify-content:space-between;margin-bottom:10px;}
            .month_filter{padding-left:5.5rem;}
            .print_pdf{justify-content:flex-start !important;padding:0 5px;}
            .salary_detail_banner{padding:0;width:80%;}
            .paycheck_salray{font-size:16px;}
            .total_price_month{font-size:24px;}
            .status_salary_month p{margin:0;}
            .salary_detail_table{width:100%;margin:auto;}
            .title_salary_detail{width:100%;margin:0;}
            .salary_price{width:50%;}
            .title_salary_detail p{font-size:14px;}
            .list_field_data{margin-bottom:1rem;}
            .price_mobile{font-size:16px}
            .container_mobile form{padding:10px;}
            .print_salary_price{padding:0}
            .list_salry div{width:37.4%;}
            .list_salry div:nth-child(1){width:45%;}
            .list_salry div:nth-child(2){width:25%;}
            .list_salry div:nth-child(3){width:30%;}
            .list_salry p{line-height:19px;}
            .list_salry div{line-height:20px;}
            .subheader{padding-left:1rem }
        }
        .table_ .list_salry {
            padding: 0 0.5rem;
        }
        .table_ .list_salry:nth-child(2n+2) {
            background: #F3F6F9;
        }
        .odd_background:nth-child(2n+2) {
            background: #F3F6F9;
        }
        .odd_background_minus:nth-child(2n+2) {
            background: #F3F6F9;
        }
        .is_hidden{
            display: none !important;]
        }
        .color_hq{
            color: #3A528F;
        }
        .detail_salary_view_admin{
            padding-left: 1rem;
            font-weight: bold;
            font-size: 1.4rem;
        }
        /*add more plus and minus*/
        .add_more_plus:hover, .remove_plus_minus:hover, .hoverPointer:hover {
            cursor: pointer;
        }
        /*.odd_background {*/
        /*    padding: 0 10px;*/
        /*}*/
        .odd_background .alig_odd{
            display: flex;
            align-items: center;
        }
        .add_layout {
            padding: 0;
        }
        .pdct{
            padding: 0 10px;
        }
        .head-left{
            color: #FFFFFF;
            vertical-align: middle !important;
            text-align: center !important;
            width: 120px !important;
            padding: 0px !important;
        }
        .rotate {
            transform:  rotate(-90deg);
            white-space: nowrap !important;
            margin: 0px !important;
        }
        .top-right{
            background-color: #FFF2CC;
        }
        .top-right2{
            background-color: #FCE5CD;
        }
        .top-right3{
            background-color: #F4CCCC;
        }
        .top-right4{
            background-color: #CFE2F3;
        }
        .minus-money{
            color: #FF0000;
        }
        .ot-money{
            color: #4A86E8;
        }
        .cc-money{
            color: #FF6D01;
        }
        .bg-cc{
            background-color: #FF6D01;
        }
        .plus-money{
            color: #008F02;
        }
        .table td{
            border: 1px solid black;
            /* margin: 5px !important; */
        }
        .table{
            font-size: 15px;
            font-family: Arial;
            font-weight: bold !important;
            border-collapse: inherit;
            border-spacing: 0px;
        }
        .text-money{
            width: 110px !important;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column-fluid container container_mobile">
        <div class="w-100">
            <div class="d-flex flex-row">
                <form action="" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" name="month_filter" id="dateYear_select">
                    <div class="row w-100 filter_mobile">
                   <div class="col-md-6 row align-items-center row-mobile">
                       <div class="fillter col-md-5 col-sm-5 col-filter-left">
                               <input type="" autocomplete="off"  name="month_filter" id="month_filter" class="month_filter form-control form-control-sm" placeholder="" value="{{ $month }}">
                               <span class="month__apend">{{ __('xin_employee_loan_time_single_month') }}</span>
                       </div>
                       <div class="search_salary col-md-3 col-sm-3 col-filter-right">
                           <button type="submit" class="fill_salary_detail btn btn-primary w-100">
                               {{ __('search') }}
                               <i class="flaticon-search pr-0 pl-3"></i>
                           </button>
                       </div>
                   </div>
               </div>
                </form>
            </div>
                @if(request()->has('salary_paycheck_id'))
                <input type="hidden" name="salary_month" id="month_salary" value="{{ $salary_paychecks->month }}">
                <input type="hidden" name="employe_id" id="employe_id" value="{{ $salary_paychecks->employee_id }}">
                @endif
            <!-- body content -->
            <div class="appen__contennt">
                <div class="content_salary_body bg-white mt-5">
                    <div class=" text-center">
                        <!-- banner paycheck -->
                        <div class="salary_detail_banner text-center">
                            <div class="salary_info">
                                <div class="salary_info_company  reset-pm">
                                    <div class="paycheck_salray">
                                        <span class="paycheck font-weight-bold">{{ __('Phiếu lương tháng') }} <span style="background-color: #FFFF00;">{{ $months }}</span>/{{ $years }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- banner paycheck -->
                    </div>
                    <!-- table info salary -->
                    <div class="salary_detail_table">
                        <div class="row justify-content-between w-100 reset-pm pd_t">
                            <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class=" align-middle">{{__('dashboard_employee_id')}}: </td>
                                    <td class="text-center align-middle" style="background-color: #FFFF00;">{{Auth::user()->employee_id}}</td>
                                    <td class="top-right align-middle">{{__('Lương HĐ chính thức')}}</td>
                                    <td class="text-right align-middle top-right">@if(isset($salary_paychecks)) @if($salary_paychecks->basic_salary) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->basic_salary.'đ')  }}  @else {{__('0đ')}} @endif @else {{__('0đ')}} @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle">{{__('dashboard_fullname')}}: </td>
                                    <td class="text-center align-middle">{{Auth::user()->last_name }} {{ Auth::user()->first_name}}</td>
                                    <td class="top-right align-middle">{{__('Lương TB 1 ngày (CT)')}}</td>
                                    <td class="text-right align-middle top-right">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->salary_perday ? $salary_paychecks->salary_perday.'đ' : '0đ')}} @else 0đ @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle">{{__('Khối: ')}}</td>
                                    <td class="text-center align-middle">@if(isset($salary_paychecks)) {{$salary_paychecks->department_grade ? $salary_paychecks->department_grade : ""}} @else {{__('Không tìm thấy khối')}} @endif</td>
                                    <td class="top-right2 align-middle">{{__('Lương HĐ TV/HV/PT')}}</td>
                                    <td class="text-right top-right2 align-middle">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->trail_salary ? $salary_paychecks->trail_salary.'đ' : __('0đ'))}} @else {{__('0đ')}} @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle">{{__('Bộ phận: ')}}</td>
                                    <td class="text-center align-middle">{{Auth::user()->department->department_name}}</td>
                                    <td class="top-right2 align-middle">{{__('Lương TB 1 ngày TV/HV/PT')}}</td>
                                    <td class="text-right top-right2 align-middle">@if(isset($salary_paychecks)) {{app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->salary_perday_trail ? $salary_paychecks->salary_perday_trail.'đ' : '0đ') }} @else {{__('0đ')}} @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle">{{__('Tình trạng: ')}}</td>
                                    <td class="text-center align-middle text-light" style="background-color: #34A853;">@if(Auth::user()->wages_type == 1)  {{__('Chính thức')}} @else {{__('Thử việc')}} @endif</td>
                                    <td class="top-right3 align-middle">{{__('Lương Khoán')}}</td>
                                    <td class="text-right align-middle top-right3">@if(isset($salary_paychecks)) {{app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->net_salary ? $salary_paychecks->net_salary.'đ' : '0đ')}} @else {{__('0đ')}} @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle">{{__('Phép tồn: ')}}</td>
                                    <td class="text-center align-middle">@if(isset($salary_paychecks)) {{$salary_paychecks->paid_leave ? $salary_paychecks->paid_leave : __('0')}} @else 0 @endif</td>
                                    <td class="top-right4 align-middle">{{__('Số ngày công được tính là "Đủ Công" trong tháng')}}</td>
                                    <td class="text-right align-middle top-right4">@if(isset($salary_paychecks)) {{$salary_paychecks->total_all_work_month ? $salary_paychecks->total_all_work_month : __('0')}} @else 0 @endif</td>
                                </tr>
                                <tr>
                                    <td class=" align-middle" style="border: 0px !important;" colspan="2"></td>
                                    <td class="top-right4 align-middle">{{__('Số ngày nghỉ không lương trong Tháng')}}</td>
                                    <td class="text-right align-middle top-right4">@if(isset($salary_paychecks)) {{ $salary_paychecks->total_leave ? $salary_paychecks->total_leave : __('0')}} @else 0 @endif</td>
                                </tr>
                            </tbody>
                            </table></div></div>

                        <!--Các khoản cộng -->
                        <div class="salary_detail_table">
                        <div class="row justify-content-between text-muted w-100 reset-pm pd_t">
                            <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class=" align-middle" style="border: 0px !important;"></td>
                                    <td class="text-center align-middle" colspan="2">{{__('Nội dung')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">{{__('Số lượng')}}</td>
                                        <td class="text-center align-middle">{{ __('Thành tiền') }}</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td rowspan="8" class="head-left align-middle" style="background-color: #34A853;"><p class="rotate">{{ __('amout_plus') }}</p></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tổng công trong tháng (Chính Thức)')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">@if(isset($salary_paychecks)) {{$salary_paychecks->total_formal_working_days ? $salary_paychecks->total_formal_working_days : __('0')}} @else 0 @endif</td>
                                        <td class="text-right align-middle plus-money text-money">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->salary_basic ? $salary_paychecks->salary_basic.'đ' : '0đ') }} @else 0đ @endif</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tổng công trong tháng (Thử việc/HV/PT)')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">@if(isset($salary_paychecks)) {{$salary_paychecks->total_trial_working_days ? $salary_paychecks->total_trial_working_days : __('0')}} @else 0 @endif</td>
                                        <td class="text-right align-middle plus-money text-money">@if(isset($salary_paychecks)) {{  app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->salary_trail ? $salary_paychecks->salary_trail.'đ' : '0đ') }} @else 0đ @endif</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tổng công đi làm nhận được phụ cấp')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">@if(isset($salary_paychecks)) {{$salary_paychecks->allowance_days ? $salary_paychecks->allowance_days : __('0')}} @else 0 @endif</td>
                                        <td class="text-right align-middle plus-money text-money">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_paychecks->allowance_days_salary ? $salary_paychecks->allowance_days_salary.'đ' : '0đ')) }} @else 0đ @endif</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền phụ cấp trách nhiệm')}}</td>
                                    <td class="text-right align-middle plus-money" colspan="2">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->salary_liability ? $salary_paychecks->salary_liability.'đ'  : __('0đ'))}} @else 0đ @endif</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Lương hiệu suất công việc')}}</td>
                                    <td class="text-right align-middle plus-money" colspan="2">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_all_kpi_month ? $salary_paychecks->total_all_kpi_month.'đ'  : __('0đ'))}} @else 0đ @endif</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Thưởng nóng')}}</td>
                                    <td class="text-right align-middle plus-money" colspan="2">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_all_work_month ? $salary_paychecks->total_all_bonus_month.'đ'  : __('0đ'))}} @else 0đ @endif</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Các khoản cộng khác')}}</td>
                                    <td class="text-right align-middle plus-money" colspan="2">@if(isset($salary_paychecks)) {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->allowance_sum_amount ? $salary_paychecks->allowance_sum_amount.'đ' : '0đ') }} @else 0đ @endif</td>
                                </tr>
                                <!--Các khoản trừ -->
                                <tr>
                                    <td rowspan="8" class="head-left align-middle" style="background-color: #FF0000;"><p class="rotate">{{ __('amout_minus') }}</p></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền Bảo Hiểm')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->total_statutory_deductions ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_statutory_deductions.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền Thuế TNCN')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->saudi_gosi_amount ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->saudi_gosi_amount.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền Phí Công Đoàn')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->union_money ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->union_money.'đ' ): app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền lương đã tạm ứng')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->total_advance ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_advance.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Các Khoản Phạt & Khấu Trừ Vào Quỹ Team')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->monetary_fine ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->monetary_fine.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền ăn')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{$salary_paychecks->total_price_datcom ?  app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_price_datcom.'đ'): app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Các Khoản Trừ Khác')}}</td>
                                    <td class="text-right align-middle minus-money" colspan="2">@if(isset($salary_paychecks))
                                        {{ $salary_paychecks->minus_money ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->minus_money.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                            </tbody>
                            </table></div></div>
                        {{-- Overtime --}}
                        <div class="salary_detail_table">
                        <div class="row justify-content-between text-muted  w-100 reset-pm pd_t">
                            <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td rowspan="6" class="head-left align-middle" style="background-color: #4A86E8;"><p class="rotate">{{ __('Overtime') }}</p></td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="2" class=" align-middle">{{__('Nội dung')}}</td>
                                    <div colspan="2">
                                        <td colspan="1" class=" align-middle">{{__('Số lượng')}}</td>
                                        <td colspan="1" class=" align-middle">{{ __('Thành tiền') }}</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Số giờ OT chính thức (đã tính hệ số)')}}</td>
                                    <div colspan="2">
                                        <td colspan="1" class="text-center align-middle">@if(isset($salary_paychecks)) {{  $salary_paychecks->total_overtime_formal ? $salary_paychecks->total_overtime_formal : '0'}} @else 0 @endif</td>
                                        <td colspan="1" class="text-right align-middle ot-money text-money">@if(isset($salary_paychecks))
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->overtime_formal_salary ? $salary_paychecks->overtime_formal_salary.'đ' : __('0đ')) }}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Số Giờ OT Thử Việc/HV/PT (đã tính hệ số)')}}</td>
                                    <div colspan="2">
                                        <td colspan="1" class="text-center align-middle">@if(isset($salary_paychecks)) {{ $salary_paychecks->total_overtime_trial ? $salary_paychecks->total_overtime_trial : '0'}} @else 0 @endif</td>
                                        <td colspan="1" class="text-right align-middle ot-money text-money">@if(isset($salary_paychecks))
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->overtime_trail_salary ? $salary_paychecks->overtime_trail_salary.'đ'  : __('0đ')) }}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tổng')}}</td>
                                    <div colspan="2">
                                        <td colspan="1" class="text-center align-middle">
                                            @if(isset($salary_paychecks))
                                            @if(isset($salary_paychecks->sum_overtime_formal_trail))
                                                {{ $salary_paychecks->sum_overtime_formal_trail}}
                                                @else {{__('0')}} @endif
                                            @else {{__('0')}} @endif
                                        </td>
                                        <td colspan="1" class="text-right align-middle ot-money">@if(isset($salary_paychecks))
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->sum_overtime_formal_trail_salary ? $salary_paychecks->sum_overtime_formal_trail_salary.'đ' : __('0đ')) }}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                            </tbody>
                            </table></div></div>

                        <!-- Tiền Chuyên cần còn lại -->
                        <div class="salary_detail_table">
                                <div class="row justify-content-between text-muted  w-100 reset-pm pd_t">
                            <table class="table table-borderless m-0">
                            <tbody>
                                <tr>
                                    <td rowspan="5" class="head-left align-middle bg-cc" ><p class="rotate">{{ __('Chuyên cần') }}</p></td>
                                </tr>
                                <tr>
                                    <td class="text-light align-middle bg-cc">{{__('Bạn có được tính Chuyên Cần?')}}</td>
                                    <td class="text-light align-middle bg-cc text-center">@if(isset($salary_paychecks)) {{$salary_paychecks->diligence_salary == 'TRUE' ? __('Có') : __('Không')}} @endif </td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">{{__('Số lượng')}}</td>
                                        <td class="text-center align-middle">{{ __('Thành tiền') }}</td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Trừ tiền do số Phút Đi Muộn')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">@if(isset($salary_paychecks)) {{ $salary_paychecks->total_all_late_month ? $salary_paychecks->total_all_late_month : '0 phút'}} @else 0 phút @endif</td>
                                        <td class="text-right align-middle cc-money text-money">@if(isset($salary_paychecks))
                                            {{ $salary_paychecks->total_all_late_month ?  '- '.app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_all_late_month_salary) : app('hrm')->getCurrencyConverter()->getUserFormat(__('0đ')) }}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Trừ tiền do số ngày nghỉ không lương')}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle">@if(isset($salary_paychecks)) {{ $salary_paychecks->total_leave ? $salary_paychecks->total_leave : '0 ngày' }} @else 0 ngày @endif</td>
                                        <td class="text-right align-middle cc-money text-money">@if(isset($salary_paychecks))
                                            {{ $salary_paychecks->total_leave_salary ? app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_leave_salary.'đ') : app('hrm')->getCurrencyConverter()->getUserFormat('0đ') }}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="2" class=" align-middle">{{__('Tiền chuyên cần nhận được')}}</td>
                                    <td class="text-right align-middle cc-money text-money" colspan="2">@if(isset($salary_paychecks))
                                        {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_attendances ? $salary_paychecks->total_attendances.'đ' : '0đ') }}
                                        @else 0đ @endif
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            <table class="table table-borderless">
                            <tbody>
                                {{-- Ghi chu --}}
                                <tr>
                                    <td rowspan="2" class="head-left align-middle" style="background-color:#0000FF;" ><p class="rotate">{{ __('Ghi chú') }}</p></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-center align-middle" style="height: 100px !important; max-width: 1100px; word-break: break-all;">{{$salary_paychecks ? $salary_paychecks->note : ''}}</td>
                                    <div colspan="2">
                                        <td class="text-center align-middle text-break">{{__('Số Tiền Thực Nhận')}}<br> <span style="font-weight: normal; font-style: italic;">{{__('(được làm tròn đến hàng nghìn)')}}</span> </td>
                                        <td class="text-center align-middle text-light text-money" style="background-color: #4285F4;">@if(isset($salary_paychecks))
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->grand_net_salary ? $salary_paychecks->grand_net_salary.'đ' : '0đ')}}
                                            @else 0đ @endif
                                        </td>
                                    </div>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-light align-middle" style="background-color: #9900FF;">{{__('Tiền Bảo Hiêm Công Ty Đóng Cho Nhân Viên')}}</td>
                                    <td colspan="4" class="text-right align-middle text-money" colspan="2">@if(isset($salary_paychecks))
                                        @if($salary_paychecks){{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->insurance_salary  ? $salary_paychecks->insurance_salary.'đ' : '0đ')}} @else {{__('0đ')}} @endif
                                        @else {{__('0đ')}} @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-light align-middle" style="background-color: #FF00FF;">{{__('Tổng Chi Phí Lương trong tháng dành cho NV')}}</td>
                                    <td colspan="4" class="text-right align-middle text-money" colspan="2">@if(isset($salary_paychecks))
                                        {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_paychecks->total_grand_net_salary ? $salary_paychecks->total_grand_net_salary.'đ' : '0đ')}}
                                        @else {{__('0đ')}} @endif
                                    </td>
                                </tr>
                            </tbody>
                            </table></div></div>
            </div></div>
            <!-- body content -->
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
        //svg-icon-2x
        var svgMinus = '<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Code\\Minus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
            '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
            '        <rect x="0" y="0" width="24" height="24"/>\n' +
            '        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>\n' +
            '        <rect fill="#000000" x="6" y="11" width="12" height="2" rx="1"/>\n' +
            '    </g>\n' +
            '</svg><!--end::Svg Icon--></span>';
        $(".show_salary").click(function () {
            $(this).parents(".table_salary_ct").find(".list_field_data").toggle();
            if($(this).parents(".table_salary_ct").find(".icons_down").hasClass("ki-arrow-next")){
                $(this).parents(".table_salary_ct").find(".icons_down").removeClass("ki-arrow-next").addClass("ki-arrow-down");
            }else{
                $(this).parents(".table_salary_ct").find(".icons_down").removeClass("ki-arrow-down").addClass("ki-arrow-next")
            }
        })

        var validatorPlus = null;
        function makeValidatorPlus(idForm) {
            validatorPlus && validatorPlus.destroy();
            var form = document.getElementById(idForm);
            validatorPlus = FormValidation.formValidation(
                form,
                {
                    fields: {
                        "title": {
                            validators: {
                                notEmpty: {
                                    message: __("field_required"),
                                }
                            }
                        },
                        "amount": {
                            validators: {
                                callback: {
                                    message: __('field_required'),
                                    callback: function(input) {
                                        if (input.value.split('.').join('') < 0){
                                            return {
                                                valid: false,
                                                message: 'Số tiền nhập là số nguyên dương',
                                            };
                                        }else{
                                            return { valid: true}
                                        }
                                    }
                                }
                                // notEmpty: {
                                //     message: __("field_required"),
                                // }
                            }
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        bootstrap: new FormValidation.plugins.Bootstrap({})
                    }
                }).on('core.form.valid', function () {
                    if(idForm === "layout_plus"){
                        var url_add = '/employees/post_data_plus';
                        var getClass = '.layout_added';
                    }else{
                        var getClass = '.layout_added_mius';
                        var url_add = '/employee_managements/post_data_minus';
                    }
                    var data_post = []
                    $(getClass).each(function () {
                        let title = $(this).find('input[name=title]').val();
                        let amount = $(this).find('input[name=amount]').val().split('.').join('');
                        data_post.push([title, amount]);
                    })
                console.log(idForm, data_post, url_add, getClass);
                    if(data_post.length > 0 ){
                        $.ajax({
                            type: "post",
                            url: url_add,
                            data: {
                                data : data_post,
                                month_salary : $("#month_salary").val(),
                                employee_id : $("#employe__id").val()
                            },
                        }).done(function (response) {
                            if (response.status === true){
                                $(getClass).remove();
                                Swal.fire(
                                    'Cập nhật thành công!',
                                    'Đang tải lại dữ liệu phiếu lương...',
                                    'success'
                                )
                                setTimeout(function(){ location.reload() }, 1000);
                            }else{
                                Swal.fire(
                                    'Thêm mới thất bại',
                                    'Vui lòng thử lại hoặc liên hệ kỹ thuật!',
                                    'error'
                                )
                            }
                        })
                    }
                });
        }

        $(document).ready(function () {
            $(".odd_background:odd, .odd_background_minus:odd").css({"background":"#F3F6F9"});
            $(".odd_background:even, .odd_background_minus:even").css({'background':"#fff"});
            $(".show_salary").trigger('click');
            $(".is_hidden").remove();
            var i = 0;
            // $('.table_').each(function (i, el) {
            //     var total = 0;
            //     $(el).find('.list_field_data').each(function (j, table) {
            //         $(table).find('.total_last').each(function (k, money_str) {
            //             var money = $(money_str).text().replace(/[\s\.₫đ]/g, '');
            //             total += parseInt(money);
            //         });
            //     });
            //     $(el).find(".title_salary_detail > .detail_price_slr > p").text(_userCurrency(total));
            // });
            $("#month_filter").datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                language: window._locale,
                autoclose:true
            });
            // $(".layout_added:odd").css({'background':'#F3F6F9'});
            $(".add_more_plus, .add_more_minus").click(function () {
                let field_data = $(this).attr('data-field');
                if(field_data === "plus"){
                    var title_selected = 'Tiêu đề khoản cộng';
                    var cls = 'layout_added';
                    var idForm = 'layout_plus';
                    $(".add_layout .remove_cloum:last").remove();
                }else{
                    var title_selected = 'Tiêu đề khoản trừ';
                    var cls = 'layout_added_mius';
                    var idForm = 'layout_minus';
                    $(".add_layout_minus .remove_cloum:last").remove();
                }
                let layout = '<div class="row '+cls+' odd_background py-2 form-group mb-0"><div class="col-md-7 reset-pm row">\n' +
                            '<span class="  alig_odd">'+title_selected+'<span>\n' +
                            '<span class="">\n' +
                            '<input type="text" name="title" class="ml-2 form-control pdct" placeholder="'+title_selected+'" >\n' +
                            '</span>\n' +
                            '</div>\n' +
                            '<div class="col-md-5 reset-pm text-right row justify-content-end align-items-center ">\n' +
                            '<span class="total_last pl-2">Số tiền</span>\n' +
                            '<span class="pl-2 pr-3">\n' +
                            '<input type="text" name="amount" class="ml-2 money_add form-control py-1" placeholder="Số tiền" >\n' +
                            '</span>\n' +
                            '<span class="remove_plus_minus">'+svgMinus+'</span></div></div>' +
                            '<div class="text-right bg-white remove_cloum"><button type="submit" data-module="'+field_data+'" class=" pb-2 btn btn-primary xin_save">Lưu</button></div>';
                if(field_data === "plus"){
                      $(".add_layout").append(layout);
                }else{
                      $(".add_layout_minus").append(layout);
                }
                $(".odd_background:odd").css({"background":"#F3F6F9"});
                $(".odd_background:even").css({'background':"#fff"});
                formUpdateIncomeType = document.getElementById(idForm);
                submitButton = formUpdateIncomeType.querySelector('[type="submit"]');
                makeValidatorPlus(idForm);
            });
            $(document).on('keyup', '.money_add', function (e) {
                var price = $(this).val();
                text  = price.split(/[a-zA-Z]/g).join("");
                string = text.replace(/_|-|\./gi, "").split(/(?=(?:\d{3})+$)/).join(".");
                $(this).val(string);
            });
            $(document).on('click','.remove_plus_minus', function () {
                if ($(this).parents().hasClass("layout_added")){
                    var getClsParent = '.add_layout';
                    var clsChild = '.layout_added';
                    var idForm = 'layout_plus';
                }else{
                    var getClsParent = '.add_layout_minus';
                    var clsChild = '.layout_added_mius';
                    var idForm = 'layout_plus';
                }
                if($(clsChild).length - 1 <= 0){
                    $(getClsParent).find(".remove_cloum").remove();
                }
                $(this).parents(clsChild).remove();
                makeValidatorPlus(idForm);
            });
        })
    </script>
@endsection
