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
            padding: 0 3rem;
        }
        .table_ .list_salry:nth-child(2n+2) {
            background: #F3F6F9;
        }
        .is_hidden{
            display: none !important;]
        }
        .color_hq{
            color: #3A528F;
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
                                        <p>{{ __('L????ng th??ng').' '.date('m-Y', strtotime($salary_payslips->salary_month)) }}</p>
                                    </div>
                                    <div class="total_price_month f36">
                                        <p class="price">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}<span></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row data_salary_month border-top pt-5 mobile-fix">
                                <div class="col-md-3 reset-pm">
                                    <p>{{ __('L????ng h???p ?????ng') }}</p>
                                    <p>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->status == 1 ? $salary_payslips->basic_salary :  $salary_payslips->salary_trial) }}</p>
                                </div>
                                <div class="col-md-3 reset-pm">
                                    <p>{{  __('dashboard_employee_id') }}</p>
                                    <p>{{ $salary_payslips->employeeSalary->employee_id }}</p>
                                </div>
                                <div class="col-md-3 reset-pm text-center">
                                    <p>{{  __('Ph??p t???n') }}</p>
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

                        <!--C??c kho???n c???ng -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('amout_plus') }}</p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->amount_plus ) }}</p>
                                    </div>
                                </div>

                                <div class="list_field_data">
                                    @if(count($basicUser) > 0)
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>Ng??y c??ng th??? vi???c</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_trial_working_days }}</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_one_day_trial']) }}</span>
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">
                                                    {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_trail']) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>Ng??y c??ng ch??nh th???c</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_formal_working_days }}</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>
                                                    {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_one_day_formal']) }}
                                                </span>
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">
                                                    {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_basic']) }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('attendance') }}</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ $salary_payslips->total_all_work_month }}</span>
                                            </div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->basic_salary-300000)/$salary_payslips->total_all_work_month) }}
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->basic_salary-300000)/$salary_payslips->total_all_work_month * $salary_payslips->total_all_work_month) }}
                                            </div>
                                        </div>
                                    @endif
                                    <!----- T???ng l???n t??ng ca ch??nh th???c ------>
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng l???n t??ng ca ch??nh th???c') }}</span></div>
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
                                        <!----- T???ng l???n t??ng ca th??? vi???c ------>
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng s??? l???n t??ng ca th??? vi???c') }}</span></div>
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
                                        <!----- T???ng c??ng ??i l??m ???????c ph??? c???p ------>
                                        @if($salary_payslips->wages_type == 1)
                                        <div class="row list_salry">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng c??ng ??i l??m ???????c ph??? c???p') }}</span></div>
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
                                        @endif
                                        <!----- Ti???n ph??? c???p tr??ch nhi???m ------>
                                        <div class="row list_salry is_hidden">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n ph??? c???p tr??ch nhi???m') }}</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ $salary_payslips->total_all_work_month }}</span>
                                            </div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>0??</span>
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">0??</span>
                                            </div>
                                        </div>
                                        <!----- Ti???n th?????ng KPI ------>
                                        <div class="row list_salry is_hidden">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n th?????ng KPI') }}*</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ $salary_payslips->total_all_work_month }}</span>
                                            </div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>0??</span>
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}
                                            </div>
                                        </div>
                                        <!----- Ti???n Th?????ng n??ng ------>
                                        <div class="row list_salry is_hidden">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n Th?????ng n??ng') }}*</span></div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>{{ $salary_payslips->total_all_work_month }}</span>
                                            </div>
                                            <div class="col-md-2 reset-pm text-right font-weight-bold">
                                                <span>0??</span>
                                            </div>
                                            <div class="col-md-3 reset-pm text-right font-weight-bold">
                                                <span class="total_last">0??</span>
                                            </div>
                                        </div>
                                        <!----- C??c kho???n c???ng kh??c ------>
                                        <div class="row list_salry is_hidden">
                                            <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('C??c kho???n c???ng kh??c') }}</span></div>
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
{{--                                <div class="list_field_data">--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('personal_income_tax') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>15</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}<span></span></span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('bhxh_bhyt') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_statutory_deductions) }}<span></span></span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('loan_amount') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_loan) }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

                        </div>
                        <!--C??c kho???n tr??? -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('amout_minus') }}</p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-danger f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat(intval($salary_payslips->saudi_gosi_amount) + intval($salary_payslips->total_statutory_deductions) + intval($salary_payslips->total_loan) + intval($salary_payslips->total_advance) + intval($salary_payslips->total_price_datcom)) }}</p>
                                    </div>
                                </div>
                                <div class="list_field_data">
                                    <!----- Ti???n b???o hi???m ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n b???o hi???m') }}</span></div>
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
                                    <!----- Ti???n thu??? thu nh???p c?? nh??n ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n thu??? TNCN') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}
                                        </div>
                                    </div>
                                    <!----- Ti???nminus   ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n vay') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_loan) }}
                                        </div>
                                    </div>
                                    <!----- Ti???n ph?? c??ng ??o??n ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n ph?? c??ng ??o??n') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_work_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}
                                        </div>
                                    </div>
                                    <!----- Ti???n l????ng ???? t???m ???ng ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n l????ng ???? t???m ???ng') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ __('50%') }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_advance) }}</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_advance) }}</span>
                                        </div>
                                    </div>
                                    <!----- Ti???n tr??? ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('C??c kho???n tr??? kh??c') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->minus_money) }}</span>
                                        </div>
                                    </div>
                                    <!----- C??c kho???n ph???t v?? kh???u tr??? v??o qu??? team ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('C??c kho???n ph???t v?? kh???u tr??? v??o qu??? team') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat(0) }}
                                        </div>
                                    </div>
                                    <!----- Ti???n ??n ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Ti???n ??n') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_day_datcom }} ng??y</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_price_datcom) }}</span>
                                        </div>
                                    </div>
                                    <!----- C??c kho???n tr???  kh??c ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('C??c kho???n tr??? kh??c') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_work_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">0??</span>
                                        </div>
                                    </div>
                                    <!--- C??c kho???n kh???u tr??? (admin ?????t) --->
                                </div>
                            </div>
                        </div>
                        <!-- Ti???n Chuy??n c???n -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('attendance_money') }}</p>
                                    </div>
                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances + $salary_payslips->total_attendances) }}</p>
                                    </div>
                                </div>
                                <div class="list_field_data">
                                    <!----- T???ng ti???n tr??? c???p ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng ti???n tr??? c???p') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances) }}</span>
                                        </div>
                                    </div>
                                    <!----- T???ng ti???n chuy??n c???n ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng ti???n chuy??n c???n') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span class="total_last">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_attendances) }}</span>
                                        </div>
                                    </div>
                                    <!----- T???ng s??? ph??t ??i mu???n ------>
                                    <div class="row list_salry">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('T???ng s??? ph??t ??i mu???n') }}</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_late_month }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_all_late_month > 300 ? $salary_payslips->total_all_late_month - 300  : '' }}</span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <!--span class="total_last">{{ $salary_payslips->total_all_late_month > 300 ?  app('hrm')->getCurrencyConverter()->getUserFormat(($salary_payslips->total_all_late_month - 300000) * 1000)  : '0??' }}</span-->
                                        </div>
                                    </div>
                                    <!----- Tr??? s??? ng??y ngh??? trong th??ng ------>
                                    <div class="row list_salry is_hidden">
                                        <div class="col-md-5 reset-pm font-weight-bold"><span>{{ __('Tr??? s??? ng??y ngh??? trong th??ng') }}*</span></div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span>{{ $salary_payslips->total_leave_days }}</span>
                                        </div>
                                        <div class="col-md-2 reset-pm text-right font-weight-bold">
                                            <span></span>
                                        </div>
                                        <div class="col-md-3 reset-pm text-right font-weight-bold">
                                            <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- S??? ti???n th???c nh???n (???????c l??m tr??n ?????n h??ng ngh??n) -->
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('S??? ti???n th???c nh???n (???????c l??m tr??n ?????n h??ng ngh??n') }}</p>
                                    </div>
                                    <div class="font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-primary f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Ti???n b???o hi???m c??ng ty ????ng cho nh??n vi??n -->
                        @if($salary_payslips->wages_type == 1)
                        <div class="table_">
                            <div class="table_salary_ct">
                                <div class="title_salary_detail row justify-content-between mt-3">
                                    <div class="title_ col-md-6 reset-pm salary_price">
                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('Ti???n b???o hi???m c??ng ty ????ng cho nh??n vi??n') }}</p>
                                    </div>
                                    <div class="font-weight-bold col-md-6 reset-pm text-right salary_price">
                                        <p class="text-primary f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat(4730000*21.5/100) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
{{--                        <!-- Ti???n chuy??n c???n -->--}}
{{--                        <div class="table_ border-top-0">--}}
{{--                            <div class="table_salary_ct">--}}
{{--                                <div class="title_salary_detail row justify-content-between mt-3">--}}
{{--                                    <div class="title_ salary_price col-md-6 reset-pm">--}}
{{--                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('xin_payroll_basic_salary') }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="detail_price_slr salary_price font-weight-bold col-md-6 reset-pm text-right">--}}
{{--                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}<span></span></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="list_field_data">--}}
{{--                                    @if(count($basicUser) > 0)--}}
{{--                                        <div class="row list_salry">--}}
{{--                                            <div class="col-md-4 reset-pm"><span>Ng??y c??ng th??? vi???c</span></div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_trial_working_days }}</span></div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold">--}}
{{--                                                {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_trail']) }}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="row list_salry">--}}
{{--                                            <div class="col-md-4 reset-pm"><span>Ng??y c??ng ch??nh th???c</span></div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_formal_working_days }}</span></div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold"><span>--}}
{{--                                                    {{ app('hrm')->getCurrencyConverter()->getUserFormat($basicUser['salary_basic']) }}--}}
{{--                                                </span></div>--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="row list_salry">--}}
{{--                                            <div class="col-md-4 reset-pm"><span>{{ __('attendance') }}</span></div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold">--}}
{{--                                                <span>{{ $salary_payslips->total_all_work_month }}</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-4 reset-pm text-right font-weight-bold">--}}
{{--                                                <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}</div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}


{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <!-- ph??? c???p -->--}}
{{--                        <div class="table_">--}}
{{--                            <div class="table_salary_ct">--}}
{{--                                <div class="title_salary_detail row justify-content-between mt-3">--}}
{{--                                    <div class="title_ col-md-6 reset-pm salary_price">--}}
{{--                                        <p class="f20 font-weight-bold show_salary color_hq">--}}
{{--                                           {{ $salary_payslips->wages_type == 2 ? __('xin_total_price_datcom') : __('xin_payroll_allowances') }}--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">--}}
{{--                                        @if($salary_payslips->wages_type == 1)--}}
{{--                                            <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances) }}</p>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="list_field_data">--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>--}}
{{--                                               {{ $salary_payslips->wages_type == 2 ? __('xin_total_price_datcom') : __('lunch_allowance') }}--}}
{{--                                            </span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold">--}}
{{--                                                <span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances) }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <!--ti???n chuy??n c???n -->--}}
{{--                        <div class="table_">--}}
{{--                            <div class="table_salary_ct">--}}
{{--                                <div class="title_salary_detail row justify-content-between mt-3">--}}
{{--                                    <div class="title_ col-md-6 reset-pm salary_price">--}}
{{--                                        <p class="f20 font-weight-bold show_salary color_hq ">{{ __('attendance_money') }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">--}}
{{--                                        <p class="text-success f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_attendances) }}<span></span></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="list_field_data">--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm 2">--}}
{{--                                            <p class="reset-pm"> {{ __('number_of_minutes_to_go_late') }}--}}
{{--                                                {{ $salary_payslips->total_all_late_month > 300 ? __('more_300_minutes') : '' }}--}}
{{--                                            </p>--}}
{{--                                            @if($salary_payslips->total_leave_days >= 4)--}}
{{--                                                <smal class="text-danger pl-4 font-weight-bold f12">{{ __('your_more_than_4days') }}</smal>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ $salary_payslips->total_all_late_month }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_attendances) }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <!--c??c kho???n tr??? -->--}}
{{--                        <div class="table_">--}}
{{--                            <div class="table_salary_ct">--}}
{{--                                <div class="title_salary_detail row justify-content-between mt-3">--}}
{{--                                    <div class="title_ col-md-6 reset-pm salary_price">--}}
{{--                                        <p class="f20 font-weight-bold show_salary color_hq">{{ __('xin_deductions') }}</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="detail_price_slr font-weight-bold col-md-6 reset-pm text-right salary_price">--}}
{{--                                        <p class="text-danger f20">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount + $salary_payslips->total_statutory_deductions + $salary_payslips->total_loan) }}</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="list_field_data">--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>Ti???n ??n </span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>15</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>3.461.538 <span>VN??</span></span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('personal_income_tax') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span><!-- 15--></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}<span></span></span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('bhxh_bhyt') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_statutory_deductions) }}<span></span></span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="row list_salry">--}}
{{--                                        <div class="col-md-4 reset-pm"><span>{{ __('loan_amount') }}</span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span></span></div>--}}
{{--                                        <div class="col-md-4 reset-pm text-right font-weight-bold"><span>{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_loan) }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}

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
                                    <p class="text-left">{{ __('account_holder') }}</p>
                                    <p class="text-right">{{ $salary_payslips->bankAccount != null ?  $salary_payslips->bankAccount->account_title : '' }}</p>
                                </div>
                                <div class="acccount_bank_user row justify-content-between">
                                    <p class="text-left">{{ __('xin_e_details_acc_number') }}</p>
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
                                    <p class="pr font-weight-bold f24 text-primary">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }} </p>
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
        $(".show_salary").click(function () {
            $(this).parents(".table_salary_ct").find(".list_field_data").toggle();
                // .css({"display":"block"})
        })
        $(document).ready(function () {
            $(".is_hidden").remove();
            var i = 0;
            // $('.table_').each(function (i, el) {
            //     var total = 0;
            //     $(el).find('.list_field_data').each(function (j, table) {
            //         $(table).find('.total_last').each(function (k, money_str) {
            //             var money = $(money_str).text().replace(/[\s\.?????]/g, '');
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
            //     .on('changeDate', function (e) {
            //     let date = e.date;
            //     let yMD = moment(date.toISOString()).format("yyyy-mm");
            //
            //     $("#dateYear_select").val(yMD);
            // });
        })
    </script>
@endsection
{{--<div class="col-md-12">--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="card">--}}
{{--                <div class="box-body ml-1 mr-1">--}}
{{--                    <div class="table-responsive" data-pattern="priority-columns">--}}
{{--                        <table class="datatables-demo table table-striped table-bordered dataTable no-footer">--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td><strong>L????ng c?? b???n:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->basic_salary) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <!-- c??ng/th??ng -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>Ng??y c??ng/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_working_days }} ng??y</span></td>--}}
{{--                            </tr>--}}
{{--                            <!--  -->--}}
{{--                            <!-- c??ng th???c t???/th??ng -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>Ng??y c??ng th???c t???/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_all_work_month }} ng??y</span></td>--}}
{{--                            </tr>--}}
{{--                            <!--  -->--}}
{{--                            <!-- L??? -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>Ngh??? l???/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_holidays }} ng??y</span></td>--}}
{{--                            </tr>--}}
{{--                            <!--  -->--}}
{{--                            <!-- ngh??? ph??p -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>Ngh??? ph??p/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_leave_days }} ng??y</span></td>--}}
{{--                            </tr>--}}
{{--                            <!--  -->--}}
{{--                            <!-- th???i gian ?????n mu???n -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>T???ng th???i gian ?????n mu???n/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_all_late_month }} ph??t</span></td>--}}
{{--                            </tr>--}}
{{--                            <!--  -->--}}
{{--                            <!-- th???i gian v??? s???m -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>T???ng th???i v??? s???m/th??ng:</strong> <span class="pull-right">{{ $salary_payslips->total_all_leave_month }} ph??t</span></td>--}}
{{--                            </tr>--}}
{{--                            <!-- T???ng c??ng OT trong th??ng -->--}}
{{--                            <!--  -->--}}
{{--                            <tr>--}}
{{--                                <td><strong>Chuy??n c???n:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->money_pc) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <!-- -->                                    <tr>--}}
{{--                                <td><strong>T???ng s??? ph??? c???p:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_allowances) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>T???ng s??? cho vay:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_loan) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Ti???n BH b???t bu???c:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_statutory_deductions) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>T???ng ti???n ?????t c??m/th??ng:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->total_price_datcom) }}</span></td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td><strong>T???ng thu tr?????c thu???:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->grand_net_salary) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>Kh???u tr??? thu???:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->saudi_gosi_amount) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td><strong>S??? ti???n ???? tr???:</strong> <span class="pull-right">{{ app('hrm')->getCurrencyConverter()->getUserFormat($salary_payslips->net_salary) }}</span></td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
