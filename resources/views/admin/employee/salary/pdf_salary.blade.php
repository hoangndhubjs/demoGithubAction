<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $pdf_salary }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<style>

    .company_info {
        padding: 20px 0;
    }
    .salary_head {
        text-align: center;
        padding: 20px 0;
    }
    .salary_title {
        font-size: 20px;
        font-weight: bold;
    }
    table {
        width: 100%;
        margin: 20px 0;
    }
    /*.date-salary{*/
    /*    font-weight:bold;*/
    /*}*/
    .header {
        border-bottom: 1px solid;
    }
    .company_address p{margin: 0}
</style>

@if($payslip_id)
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
                <div class="salary_title">{{ __('left_payslips') }}</div>
                <div><span class="month_salary">{{ __('Month') }} </span><span class="date-salary">{{ $payslip_id['salary_month'] }}</span></div>
            </div>
            <!-- table -->
            {{--                {{dd($payslip_id)}}--}}
            <table cellpadding="2" cellspacing="0" border="1">
                <tr bgcolor="#69e48a">
                    <td colspan="4" align="center"><strong>{{ __('info_employee') }}</strong></td>
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
                    <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['total_attendances']) }}</td>
                </tr>
            </table>
            <table cellpadding="2" cellspacing="0" border="1">
                <tr bgcolor="#69e48a">
                    <td colspan="4" align="center"><strong>{{ __('xin_deductions') }}</strong></td>
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

            <table cellpadding="2" cellspacing="0" border="1">
                <tr bgcolor="#69e48a">
                    <td colspan="2" align="center"><strong>{{ __('xin_description') }}</strong></td>
                    <td colspan="3" align="center"><strong>{{ __('xin_payslip_earning') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">{{ __('xin_payroll_basic_salary') }}</td>
                    <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['basic_salary']) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><strong>{{ __('grand_net_salary') }}</strong></td>
                    <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['grand_net_salary']) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="" align="center"><strong></strong></td>
                    <td colspan="4" bgcolor="#69e48a" align="center"><strong>{{ __('xin_total_salary_month') }}</strong></td>
                </tr>
                <tr>
                    <td  colspan="" align="center"><strong></strong></td>
                    <td colspan="4" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($payslip_id['net_salary']) }}</strong></td>
                </tr>
            </table>
            <table cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td align="right" colspan="1"><strong>{{ __('computer_generated') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@else
    <div class="f20 text-center p-5 mt-5">
        <p class="f24"><span>{{  __('not_found_data_salary').': '.app('request')->get('month_filter') }}</span></p>
    </div>
@endif
</body>
</html>
