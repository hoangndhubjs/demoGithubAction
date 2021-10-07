<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SalaryPayslip;
//use Illuminate\Support\Facades\View;
use PDF;
class sendPayroll extends Mailable
{
    use Queueable, SerializesModels;
    private $payslip_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payslip_id)
    {
        $this->payslip_id = $payslip_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $getOneSalary  = SalaryPayslip::with([
            'employeeSalary',
            'employeeCompany',
            'department',
            'designation',
            'bankAccount',
            'employee.office_shift'
        ])->find($this->payslip_id);
        $month_Year = Carbon::createFromFormat('Y-m', $getOneSalary->salary_month);
        $dates = $month_Year->format('m-Y');
        $subject = __('company_send_mail_payroll').' Thanh toán lương tháng: '.$month_Year->format('m-Y');

        if ($getOneSalary->wages_type == 1){
            $status_wages = 'Chính thức';
        }elseif($getOneSalary->wages_type == 2){
            $status_wages = 'Thử việc';
        }elseif($getOneSalary->wages_type == 3){
            $status_wages = 'Partime';
        }elseif($getOneSalary->wages_type == 4){
            $status_wages = 'thực tập';
        }else{
            $status_wages = '--';
        }
        return $this->subject($subject)->view('admin.payroll.mail', compact('getOneSalary','dates','status_wages'));
    }
}
