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
        /**/ .salary_body{background:linear-gradient(89.95deg, #1689FF 0.04%, #38D0E5 99.95%);border-radius:6px;color:#fff;}
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
        .pd_t{padding: 2.5rem 0 2rem 0;}
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
        .title_add_more {
            width: 50%;
            padding: 4px 0;
        }
        ._xin_save{
            padding: 0.5rem 2rem;
            font-size: 1rem;
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
        ._patf {
            padding-left: 5rem;
        }
        ._patfr {
            padding-right: 1rem;
        }
        .fv-plugins-message-container{
            display: none !important;
        }
        .is-valid {
            padding: 0 10px !important;
        }
        .pdct{
            padding: 0 10px;
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
                       @if(request()->has('payslip_id'))
                           <span class="detail_salary_view_admin">{{ 'Bảng lương nhân viên: '.$salary_payslips->employeeSalary->first_name.' '.$salary_payslips->employeeSalary->last_name }}</span>
                       @else
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
                        @endif
                   </div>
                   <div class="col-md-6 print_salary_price">
                       <div class="row print_salary justify-content-end print_pdf">
                           <div class="mx-3">
                               <a {{ $salary_payslips ? 'target="_blank"': '' }} href="{{ $salary_payslips ? url('/employees/create_form_pdf?month_filter='.$month) : 'javascript:;' }}" class="btn-primary btn">{{ __('print_salary_employee') }}</a>
                           </div>
                           <div class="border border-primary mx-3 rounded">
                               <a  href="{{ $salary_payslips ? url('employees/pdf_salary?month_filter='.$month) : 'javascript:;' }}"class="btn text-primary px-2">{{ __('xin_download') }}</a>
                           </div>
                       </div>
                   </div>
               </div>
                </form>
            </div>
            @if($salary_payslips)
                @if(request()->has('payslip_id'))
                <input type="hidden" name="salary_month" id="month_salary" value="{{ $salary_payslips->salary_month }}">
                <input type="hidden" name="employe__id" id="employe__id" value="{{ $salary_payslips->employee_id }}">
                @endif
            <!-- body content -->
            <div class="appen__contennt">
                <div class="content_salary_body bg-white mt-5">
                    <div class="salary_body">
                        <!-- banner paycheck -->
                        <div class="salary_detail_banner">
                            <div class="salary_info row">
                                <div class="salary_info_company col-md-6 reset-pm">
                                    <div class="info_company mobile-fix">
                                        <div class="name_company"><span class="f16">{{ $salary_payslips->employeeCompany->name }}</span></div>
                                        <div class="addrres_company"><span class="f14">{{ $salary_payslips->employeeCompany->address_2 }}</span></div>
                                    </div>
                                    <div class="paycheck_salray">
                                        <span class="paycheck font-weight-bold">{{ __('paycheck_employee') }}</span>
                                    </div>
                                </div>
                                <div class="salary_price_status col-md-6 text-right reset-pm">
                                    <div class="status_salary_month">
                                        <p class="f16">{{ $salary_payslips->status == 2 ? __('xin_payroll_paid') : __('xin_payroll_unpaid') }}</p>
                                        <p>{{ __('Lương tháng').' '.date('m-Y', strtotime($salary_payslips->salary_month)) }}</p>
                                    </div>
                                    <div class="total_price_month f36">
                                        <p class="price">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}<span></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row data_salary_month border-top pt-5 mobile-fix">
                                <div class="col-md-3 reset-pm">
                                    <p>{{ __('Lương hợp đồng') }}</p>
                                    <p>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->wages_type == 1 ? $salary_payslips->basic_salary : $salary_payslips->trail_salary) }}</p>
                                </div>
                                <div class="col-md-3 reset-pm">
                                    <p>{{  __('dashboard_employee_id') }}</p>
                                    <p>{{ $salary_payslips->employeeSalary->employee_id }}</p>
                                </div>
                                <div class="col-md-3 reset-pm text-center">
                                    <p>{{  __('Phép tồn') }}</p>
                                    <p>{{ $salary_payslips->paid_leave }}</p>
                                </div>
                                <div class="col-md-3 text-right reset-pm">
                                    <p>{{ __('status_employee') }}</p>
                                    <p>{{ __('dashboard_employees').': '.Auth::user()->office_shift->shift_name }}</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- banner paycheck -->
                    </div>
                    <!-- table info salary -->
                    <div class="salary_detail_table">
                        <div class="row justify-content-between text-muted f20 border-bottom w-100 reset-pm pd_t">
                            <div class="col-md-5 text-left salary_price price_mobile pl-0">
                                <p class="reset-pm pl-0 ">{{ __('xin_description') }}</p>
                            </div>
                            <div class="col-md-2 text-right salary_price price_mobile pr-0">{{ __('xin_quantity') }}</div>
                            <div class="col-md-2 text-right salary_price price_mobile pr-0">{{ __('xin_amount') }}</div>
                            <div class="col-md-3 text-right salary_price price_mobile pr-0">{{ __('amounted_employee') }}</div>
                        </div>

                        <!--Các khoản cộng -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('amout_plus') }}<span class="icons_dropdown"><i class="icons_down  text-dark-50 ki ki-arrow-next"></i></span></p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->amount_plus ) }}</p>
                                    </div>
                                </div>
                                <div class="list_field_data">
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('attendance') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_working_days }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>
{{--                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->basic_salary-300000)/$salary_payslips->total_all_work_month) }}--}}
                                                </span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            {{--                                                <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->basic_salary-300000)/$salary_payslips->total_all_work_month * $salary_payslips->total_all_work_month) }}--}}
                                        </div>
                                    </div>
{{--                                    @if(count($basicUser) > 0)--}}
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>Ngày công thử việc</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_trial_working_days }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ $basicUser && $basicUser['salary_one_day_trial'] ? app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_one_day_trial']) : 0 }}</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">
                                                {{  app('hrm')->getCurrencyConverter()->getUserFormat($basicUser && $basicUser['salary_trail'] ? $basicUser['salary_trail'] : 0 ) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>Ngày công chính thức</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_formal_working_days }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>
                                                {{  app('hrm')->getCurrencyConverter()->getUserFormat($basicUser && $basicUser['salary_one_day_formal'] ? $basicUser['salary_one_day_formal'] : 0) }}
                                            </span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">
                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser && $basicUser['salary_basic'] ? $basicUser['salary_basic'] : 0) }}
                                            </span>
                                        </div>
                                    </div>
{{--                                @else--}}
{{--                                @endif--}}
                                    <!----- Tổng lần tăng ca chính thức ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tổng lần tăng ca chính thức') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_overtime_formal }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser ? $basicUser['overtime_formal'] : 0) }}
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser ? $basicUser['overtime_formal'] :0) }}
                                        </div>
                                    </div>
                                    <!----- Tổng lần tăng ca thử việc ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tổng số lần tăng ca thử việc') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_overtime_trial }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser ? $basicUser['overtime_trail'] : 0) }}
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat( $basicUser ? $basicUser['overtime_trail'] : 0) }}
                                        </div>
                                    </div>
                                    <!----- Tổng công đi làm được phụ cấp ------>
                                    {{--@if($salary_payslips->wages_type == 1)--}}
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tổng công đi làm được phụ cấp') }}</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ ($salary_payslips->allowance_days) }}</span>
                                            </div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat(25000) }}
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold ">
                                                <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->allowance_days) * 25000) }}
                                            </div>
                                        </div>
                                    {{-- @endif--}}
                                    <!-- Ngày nghỉ lễ -->
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('relax_holiday') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_holidays + $salary_payslips->total_holidays_trail_work }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
{{--                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat(25000) }}--}}
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold ">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['total_salary_holidays']) }}
                                        </div>
                                    </div>
                                    <!-- Ngày nghỉ lễ -->
                                    <!----- Các khoản cộng được set  ------>
                                    <div class="row list_salry p-0">
                                        <div class="col-md-6 reset-pm font-weight-bold"><span>{{ __('other_pluses') }}</span></div>
                                        <div class="col-md-6 reset-pm text-right font-weight-bold ">
                                            <span class="total_last">
                                            {{ app('hrm')->getCurrencyConverter()->getUserFormat($allowance_sum) }}
                                            </span>
                                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin() && request()->has('payslip_id'))
                                                <span class="add_more_plus" data-field="plus">
                                                    {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg', 'svg-icon-primary pl-2') }}
                                                </span>
                                            @endif
                                        </div>
                                        <!----- Các khoản cộng  khác ------>
                                        <div class="col-lg-12 col-xs-12 col-xl-12 p-0">
                                            <form action="" id="layout_plus" class="add_layout">
                                            @foreach($allowance_all as $allowance)
                                                <div class="row odd_background">
                                                    <div class="col-md-6 reset-pm">
                                                        <span class="_patf">{{ $allowance->allowance_title }}</span>
                                                    </div>
                                                    <div class="col-md-6 reset-pm text-right font-weight-bold">
                                                        <span class="total_last _patfr">{{ app('hrm')->getCurrencyConverter()->getUserFormat($allowance->allowance_amount) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </form>
                                        </div>
                                    </div>
                                    <!----- Tiền thưởng KPI ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền thưởng KPI') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_work_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>0đ</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}
                                        </div>
                                    </div>
                                    <!----- Tiền Thưởng nóng ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền Thưởng nóng') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_work_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>0đ</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">0đ</span>
                                        </div>
                                    </div>
                                    <!----- Các khoản cộng khác ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Các khoản cộng khác') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_work_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Các khoản trừ -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('amout_minus') }}<span class="icons_dropdown"><i class="icons_down  text-dark-50 ki ki-arrow-next"></i></span></p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-danger f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat(intval($salary_payslips->saudi_gosi_amount) + intval($salary_payslips->total_statutory_deductions) + intval($salary_payslips->total_loan) + intval($salary_payslips->total_advance) + intval($salary_payslips->total_price_datcom) + intval($salary_payslips->minus_money)) }}</p>
                                    </div>
                                </div>
                                <div class="list_field_data">
                                    <!----- Tiền bảo hiểm ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền bảo hiểm') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                           <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_statutory_deductions) }}
                                        </div>
                                    </div>
                                    <!----- Tiền thuế thu nhập cá nhân ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền thuế TNCN') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>
{{--                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}--}}
                                            </span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}
                                        </div>
                                    </div>
                                    <!----- Tiền trừ (tam thoi an)  ------>
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền vay') }}</span></div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-3 reset-pm text-right font-weight-bold">--}}
{{--                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_loan) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!----- Tiền phí công đoàn ------>
{{--                                    <div class="row list_salry is_hidden">--}}
{{--                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền phí công đoàn') }}*</span></div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span>{{ $salary_payslips->total_all_work_month }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-3 reset-pm text-right font-weight-bold">--}}
{{--                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!----- Tiền lương đã tạm ứng ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền lương đã tạm ứng') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ __('') }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>
{{--                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_advance) }}--}}
                                            </span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_advance) }}</span>
                                        </div>
                                    </div>
                                    <!----- Các khoản phạt và khấu trừ vào quỹ team ------>
{{--                                    <div class="row list_salry is_hidden">--}}
{{--                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Các khoản phạt và khấu trừ vào quỹ team') }}*</span></div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-3 reset-pm text-right font-weight-bold">--}}
{{--                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!----- Tiền ăn ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tiền ăn') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>
{{--                                                {{ $salary_payslips->total_day_datcom }} ngày--}}
                                            </span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_price_datcom) }}</span>
                                        </div>
                                    </div>
                                    <!----- Tiền trừ được set ------>
                                    <div class="row list_salry p-0">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Các khoản trừ khác') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">
                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->minus_money) }}
                                            </span>
                                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin() && request()->has('payslip_id'))
                                                <span class="hoverPointer add_more_minus" data-field="minus">
                                                    {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg', 'svg-icon-primary pl-2') }}
                                                </span>
                                            @endif
                                        </div>
                                        <!----- Các khoản trừ  khác ------>
                                        <div class="col-lg-12 col-xs-12 col-xl-12 p-0">
                                            <form action="" id="layout_minus" class="add_layout_minus">
                                            @foreach($money_all as $minus)
                                                <div class="row odd_background_minus">
                                                    <div class="col-md-6 reset-pm"><span class="_patf">{{ $minus->title }}</span></div>
                                                    <div class="col-md-6 reset-pm text-right font-weight-bold">
                                                        <span class="total_last _patfr">{{ app('hrm')->getCurrencyConverter()->getUserFormat($minus->money) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </form>
                                        </div>
                                    </div>

                                    <!--- Các khoản khấu trừ (admin đặt) --->
                                </div>
                            </div>
                        </div>
                        <!-- Tiền Chuyên cần còn lại -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('attendance_money_remaining') }}<span class="icons_dropdown"><i class="icons_down  text-dark-50 ki ki-arrow-next"></i></span></p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_attendances) }}</p>
                                    </div>
                                </div>
                                <div class="list_field_data">
                                    <!----- Tổng tiền trợ cấp ------>
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tổng tiền trợ cấp') }}</span></div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-2 reset-pm text-right font-weight-bold">--}}
{{--                                            <span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-3 reset-pm text-right font-weight-bold">--}}
{{--                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances) }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!----- Tổng tiền chuyên cần ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tổng tiền chuyên cần') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(500000) }}</span>
                                        </div>
                                    </div>
                                    <!----- Tổng số phút đi muộn ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Số phút đi muộn') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_late_month.' phút' }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
{{--                                            <span>{{ $salary_payslips->total_all_late_month > 300 ? $salary_payslips->total_all_late_month - 300  : '' }}</span>--}}
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ $salary_payslips->total_all_late_month > 300 ?  app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->total_all_late_month - 300) * 1000)  : '0đ' }}</span>
                                        </div>
                                    </div>
                                    <!----- Trừ số ngày nghỉ trong tháng ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Trừ số ngày nghỉ trong tháng') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $data_leave_days['total_day'].' ngày' }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($data_leave_days['total_salary_leave']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Số tiền thực nhận (được làm tròn đến hàng nghìn) -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('Số tiền thực nhận') }}</p>
                                    </div>
                                    <div class="font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-primary f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Tiền bảo hiểm công ty đóng cho nhân viên -->
                        @if($salary_payslips->wages_type == 1)
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('Tiền bảo hiểm công ty đóng cho nhân viên') }}</p>
                                    </div>
                                    <div class="font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-primary f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat(4730000*21.5/100) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- table info salary -->
                </div>
                <!-- end body content -->
                <!-- footer-backcount-salary-user -->
                <div class="footer_salary_user ">
                    <div class="row reset-pm salary_detail_table">
                        <div class="col-md-6">
                            <div class="col-md-9 f14">
                                <div class="sub_footer row justify-content-between">
                                    <p class="font-weight-bold">{{ __('cash_bank_account') }}</p>
                                </div>
                                <div class="acccount_bank_user row justify-content-between">
                                    <p class="text-left">{{ __('account_holder') }}:</p>
                                    <p class="text-right">{{ $salary_payslips->bankAccount != null ?  $salary_payslips->bankAccount->account_title : '' }}</p>
                                </div>
                                <div class="acccount_bank_user row justify-content-between">
                                    <p class="text-left">{{ __('xin_e_details_acc_number') }}:</p>
                                    <p class="text-right">{{ $salary_payslips->bankAccount != null ? $salary_payslips->bankAccount->account_number : '' }}</p>
                                </div>
                                <div class="acccount_bank_user row justify-content-between">
                                    <p class="text-left">{{ __('name_bank') }}:</p>
                                    <p class="text-right">{{  $salary_payslips->bankAccount != null ? $salary_payslips->bankAccount->bank_name : '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 net_salary_user">
                            <div class="group-gnet-salary">
                                <div class="total_">
                                    <p class="text-uppercase f14">{{ __('xin_grand_total') }}</p>
                                </div>
                                <div class="price_last">
                                    <p class="pr font-weight-bold f24 text-primary">
                                        {{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary + ($salary_payslips->wages_type == 1 ? 4730000*21.5/100 : 0)) }}
                                    </p>
                                </div>
                                <div class="vat-lary">
                                    <p class="f14">{{ __('taxes_included') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer-backcount-salary-user -->
            </div>
            <!-- body content -->
            @else
                <div class="f20 text-center p-5 mt-5">
                    <p class="f24"><span>{{ __('not_found_data_salary').': '.$month }}</span></p>
                </div>
            @endif
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
                            '<span class="_patf  alig_odd">'+title_selected+'<span>\n' +
                            '<span class="title_add_more">\n' +
                            '<input type="text" name="title" class="ml-2 form-control pdct" placeholder="'+title_selected+'" >\n' +
                            '</span>\n' +
                            '</div>\n' +
                            '<div class="col-md-5 reset-pm text-right row justify-content-end align-items-center _patfr">\n' +
                            '<span class="total_last pl-2">Số tiền</span>\n' +
                            '<span class="pl-2 pr-3">\n' +
                            '<input type="text" name="amount" class="ml-2 money_add form-control py-1" placeholder="Số tiền" >\n' +
                            '</span>\n' +
                            '<span class="remove_plus_minus">'+svgMinus+'</span></div></div>' +
                            '<div class="text-right bg-white remove_cloum"><button type="submit" data-module="'+field_data+'" class="_xin_save pb-2 btn btn-primary xin_save">Lưu</button></div>';
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