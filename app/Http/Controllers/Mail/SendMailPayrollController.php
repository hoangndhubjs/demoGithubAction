<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendPayroll;
use App\Jobs\SendEmailSalary;


class SendMailPayrollController extends Controller
{
    public function sendMailPayroll(Request $request){

        $payslip = $request->payslipId_arr;
        if($request->single_payslip){
            $payslip_id = $request->single_payslip;
        }else{
            $payslip_id = implode(",", $payslip);
        }
        SendEmailSalary::dispatch($payslip_id);
    }
}
