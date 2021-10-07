<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mailer</title>
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

@if($getOneSalary)
    <div class="container">
        <div class="div_bd">
            <div class="header">
                <div class="company_info">

                    <div class="company_address">
                        <div class="company_name"><strong>{{ $getOneSalary->employeeSalary->name }}</strong></div>
                        <p>{{ $getOneSalary->employeeSalary->address_1.' '.$getOneSalary->employeeSalary->address_2 }}</p>
                    </div>
                </div>
            </div>
            <div class="content_salary_user">
                <div style="text-align: center;margin-bottom: 1.5rem;" class="salary_head">
                    <div  class="salary_title"><h1 style="margin-bottom: 0;">{{ __('left_payslips') }}</h1></div>
                    <div style="font-size: 1rem;font-weight: 700;">
                        <span class="month_salary">{{ __('Month') }} </span>
                        <span class="date-salary">{{ $dates }}</span>
                    </div>
                </div>
                <!-- table -->
                <table style="margin: auto;width: 50%;margin-bottom: 1.5rem;" cellpadding="2" cellspacing="0" border="1">
                    <tr bgcolor="#1689ff" style="color:#fff">
                        <td colspan="4" align="center"><strong>{{ __('info_employee') }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_name') }}</td>
                        <td>{{ $getOneSalary->employeeSalary->last_name.' '.$getOneSalary->employeeSalary->first_name }}</td>
                        <td>{{ __('dashboard_employee_id') }}</td>
                        <td>{{ $getOneSalary->employeeSalary->employee_id }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Tình trạng') }}</td>
                        <td>{{ $status_wages }}</td>
                        <td>{{ __('total_phepton_had') }}</td>
                        <td>{{ $getOneSalary->paid_leave }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('left_department') }}</td>
                        <td>{{ $getOneSalary->department ? $getOneSalary->department->department_name : '' }}</td>
                        <td>{{ __('dashboard_designation') }}</td>
                        <td>{{ $getOneSalary->designation ? $getOneSalary->designation->designation_name : ''}}</td></td>
                    </tr>
                    <tr>
                        <td>{{ __('total_holidays') }}</td>
                        <td>{{ $getOneSalary->total_holidays }}</td>
                        <td>{{ __('Ngày công trong tháng') }}</td>
                        <td>{{ $getOneSalary->total_working_days }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('left_office_shifts') }}</td>
                        <td>{{ $getOneSalary->employee->office_shift->shift_name  }}</td>
                        <td>{{ __('Tổng số ngày nghỉ có lương') }}</td>
                        <td>{{ $getOneSalary->total_leave_days }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_total_day_datcom') }}</td>
                        <td>{{ $getOneSalary->total_day_datcom }}</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                </table>
                <!-- plus amounts -->
                <table style="margin: auto;width: 50%;margin-bottom: 1.5rem;" cellpadding="2" cellspacing="0" border="1">
                    <tr bgcolor="#1689ff" style="color:#fff">
                        <td colspan="4" align="center"><strong>{{ __('amout_plus') }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_salary_total_month') }}</td>
                        <td>{{ $getOneSalary->total_all_work_month }}</td>
                        <td>{{ __('xin_payroll_allowances') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->total_attendances) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('total_overtime_formal') }}</td>
                        <td>{{ $getOneSalary->total_overtime_formal }}</td>
                        <td>{{ __('total_overtime_trail') }}</td>
                        <td>{{  $getOneSalary->total_overtime_trial }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('day_formal_work') }}</td>
                        <td>{{ $getOneSalary->total_formal_working_days }}</td>
                        <td>{{ __('day_trail_work') }}</td>
                        <td>{{  $getOneSalary->total_trial_working_days }}</td>
                    </tr>
                    @if($getOneSalary->wages_type == 1)
                        <tr>
                            <td>{{ __('total_work_allowance') }}</td>
                            <td>{{ $getOneSalary->total_formal_working_days }}</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                    @endif
                </table>
                <!-- minus -->
                <table style="margin: auto;width: 50%;margin-bottom: 1.5rem;" cellpadding="2" cellspacing="0" border="1">
                    <tr bgcolor="#1689ff" style="color:#fff">
                        <td colspan="4" align="center"><strong>{{ __('xin_deductions') }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('xin_employee_set_loan_deductions') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->total_loan) }}</td>
                        <td>{{ __('health_insurance') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->total_statutory_deductions) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('personal_income_tax') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->saudi_gosi_amount) }}</td>
                        <td>{{ __('advan_money') }}</td>
                        <td>{{ app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->total_advance) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('total_minus_other') }}</td>
                        <td>{{ app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->minus_money) }}</td>
                        <td>{{ __('xin_total_price_datcom') }}</td>
                        <td>{{  app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->total_price_datcom) }}</td>
                    </tr>
                </table>
                <!-- allowance -->
                <table style="margin: auto;width: 50%;margin-bottom: 1.5rem;" cellpadding="2" cellspacing="0" border="1">
                    <tr bgcolor="#1689ff" style="color:#fff">
                        <td colspan="2" align="center"><strong>{{ __('attendance_money') }}</strong></td>
                        <td colspan="3" align="center"><strong>{{ __('amounted_employee') }}</strong></td>
                    </tr>
                    <tr>
                    <tr>
                        <td> {{ __('total_late') }}</td>
                        <td> {{ $getOneSalary->total_all_late_month.' phút' }}</td>
                        <td colspan="2">
                            @if($getOneSalary->total_all_late_month > 300)
                                {{ app('hrm')->getCurrencyConverter()->getUserFormat(($getOneSalary->total_all_late_month - 300) * 1000) }}
                            @else -- @endif
                        </td>
                    </tr>

                    </tr>
                </table>
                <!-- total -->
                <table style="margin: auto;width: 50%;" cellpadding="2" cellspacing="0" border="1">
                    <tr bgcolor="#1689ff" style="color:#fff">
                        <td colspan="2" align="center"><strong>{{ __('xin_description') }}</strong></td>
                        <td colspan="3" align="center"><strong>{{ __('xin_payslip_earning') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">{{ __('xin_payroll_basic_salary') }}</td>
                        <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->basic_salary) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><strong>{{ __('grand_net_salary') }}</strong></td>
                        <td colspan="3" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->net_salary) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="" align="center"><strong></strong></td>
                        <td colspan="4" bgcolor="#1689ff" style="color:#fff" align="center"><strong>{{ __('xin_total_salary_month') }}</strong></td>
                    </tr>
                    <tr>
                        <td  colspan="" align="center"><strong></strong></td>
                        <td colspan="4" align="center"><strong>{{ app('hrm')->getCurrencyConverter()->getUserFormat($getOneSalary->grand_net_salary) }}</strong></td>
                    </tr>
                </table>
                <!-- salary paid -->
                <table style="width: 50%;margin: auto" cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td align="right" colspan="1"><strong>{{ __('computer_generated') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@else
@endif
</body>
</html>
