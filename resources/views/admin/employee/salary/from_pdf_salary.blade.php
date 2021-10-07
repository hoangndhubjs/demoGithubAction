<style>
    body {
        background-color: rgb(82, 86, 89);
        /*color: var(--primary-text-color);*/
        line-height: 154%;
        margin: 0;
    }
    .container {
        width: 50%;
        margin: auto;
        background: #fff;
        margin-top: 5%;
        padding: 10px 0 20rem 0;
        margin-bottom: 30px;
    }
    .company_info {
        padding: 20px 0;
    }
    .div_bd{
        width: 80%;
        margin: auto;
    }
    .salary_head {
        text-align: center;
        padding: 20px 0;
    }
    .salary_title {
        font-size: 25px;
        font-weight: bold;
    }
    table {
        width: 100%;
        margin: 40px 0;
    }
    .date-salary{
        font-weight:bold;
    }
    .dowload_here {
        position: fixed;
        bottom: 30px;
        right: 10px;
        background: #e6e8e9;
        border-radius: 50%;
        padding: 0.6rem;
    }
    .dowload_here:hover {
        box-shadow: 0px 0px 5px 1px #9f9999;
    }
    .header {
        border-bottom: 1px solid;
    }
    .company_address p{margin: 0}
</style>
    <div class="container">
        <div class="div_bd">
            <div class="header">
                <div class="company_info">

                    <div class="company_address">
                        <div class="company_name"><strong>{{ $payslip_id['employee_company']['name'] }}</strong></div>
                        <p>{{ $payslip_id['employee_company']['address_1'].' '.$payslip_id['employee_company']['address_2'] }}</p>
                    </div>
                </div>
            </div>
            <div class="content_salary_user">
                <div class="salary_head">
                    <div class="salary_title">Lương</div>
                    <div><span class="month_salary">Tháng </span><span class="date-salary">{{ $payslip_id['salary_month'] }}</span></div>
                </div>
                <!-- table -->
{{--                {{dd($payslip_id)}}--}}
                <table cellpadding="3" cellspacing="0" border="1">
                    <tr bgcolor="#69e48a">
                        <td colspan="4" align="center"><strong>Thông tin nhân viên</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_name') }}</td>
                        <td>{{ $payslip_id['employee_salary']['last_name'].' '.$payslip_id['employee_salary']['first_name'] }}</td>
                        <td>{{ __('dashboard_employee_id') }}</td>
                        <td>{{ $payslip_id['employee_salary']['employee_id'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('left_department') }}</td>
                        <td>{{ $payslip_id['department']['department_name'] }}</td>
                        <td>{{ __('dashboard_designation') }}</td>
                        <td>{{ $payslip_id['designation']['designation_name'] }}</td></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_payroll_pdf_dt_engage') }}</td>
                        <td>{{ $payslip_id['employee_salary']['date_of_joining'] }}</td>
                        <td>{{ __('xin_timesheet_workdays') }}</td>
                        <td>{{ $payslip_id['total_working_days'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('left_office_shifts') }}</td>
                        <td>{{ Auth::user()->office_shift->shift_name  }}</td>
                        <td>{{ __('xin_attendance_total_leave') }}</td>
                        <td>{{ $payslip_id['total_leave_days'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_title_time_late_work') }}</td>
                        <td>{{ $payslip_id['total_all_late_month'] }}</td>
                        <td>{{ __('xin_total_price_datcom') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['total_price_datcom']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_total_day_datcom') }}</td>
                        <td>{{ $payslip_id['total_day_datcom'] }}</td>
                        <td>{{ __('total_holidays') }}</td>
                        <td>{{ $payslip_id['total_holidays'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_salary_total_month') }}</td>
                        <td>{{ $payslip_id['total_all_work_month'] }}</td>
                        <td>{{ __('xin_payroll_allowances') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['money_pc']) }}</td>
                    </tr>
                </table>
                <table cellpadding="3" cellspacing="0" border="1">
                    <tr bgcolor="#69e48a">
                        <td colspan="4" align="center"><strong>Các khoản khấu trừ</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_payroll_total_loan') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['total_loan']) }}</td>
                        <td>{{ __('health_insurance') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['total_statutory_deductions']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('personal_income_tax') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['saudi_gosi_amount']) }}</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>

                </table>

                <table cellpadding="3" cellspacing="0" border="1">
                    <tr bgcolor="#69e48a">
                        <td colspan="2" align="center"><strong>Mô tả</strong></td>
                        <td  align="center"><strong>Thu nhập</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">Lương cơ bản</td>
                        <td  align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['basic_salary']) }}</strong></td>
                    </tr>
                   <tr>
                       <td colspan="2" align="center"><strong>Tổng thu trước thuế</strong></td>
                       <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['grand_net_salary']) }}</strong></td>
                   </tr>
                    <tr>
                        <td colspan="" align="center"><strong></strong></td>
                        <td colspan="3" bgcolor="#69e48a" align="center"><strong>Lương thực nhận</strong></td>
                    </tr>
                    <tr>
                        <td  colspan="" align="center"><strong></strong></td>
                        <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['net_salary']) }}</strong></td>
                    </tr>
                </table>
                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td align="right" colspan="1"><strong>Đây là phiếu do máy tính tạo và không yêu cầu chữ ký.</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<div class="dowload_here jump">
    <a href="{{ url('employees/pdf_salary', $payslip_id['payslip_id']) }}" title="tải xuống bảng lương" class="">
        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Files\Download.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
        <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
    </g></svg><!--end::Svg Icon--></span>
    </a>
</div>
