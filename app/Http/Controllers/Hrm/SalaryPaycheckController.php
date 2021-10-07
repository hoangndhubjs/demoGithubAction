<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\SalaryPaycheck;
use App\Repositories\SalaryPaycheckRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryPaycheckController extends Controller
{
    use DatatableResponseable;
    private $salary_paycheck;

    public function __construct(SalaryPaycheckRepository $salary_paycheck)
    {
        $this->salary_paycheck = $salary_paycheck;
    }
    public function user_monthly_salary_slip(Request $request)
    {
        $page_title = __('info_salary_user');
        $get_date = $request->input('month_filter');
        // dd($get_date);
        $date_before = now()->startOfMonth()->subMonth()->format("m-Y");
        $month = $get_date ?? $date_before;

        $employee_id = Auth::user()->employee_id;
        // dd($employee_id); //==> lay duoc ma nv
        // dd($month);
        if ($salary_paycheck_id = $request->salary_paycheck_id){
            $info = $this->salary_paycheck->find($salary_paycheck_id);
            $employee_id = $info->employee_id;
            $month = $info->month;
        }

        $salary_paychecks = $this->salary_paycheck->checkDataMonth($employee_id, $month);
        $months = substr($month,0,2);
        $years = substr($month,3);
        // dd($month, $months);
        // dd($salary_paychecks);
        $basicUser = [];
        return view('employees.salary.user_monthly_salary_slip', compact('salary_paychecks', 'page_title', 'month', 'months', 'years', 'basicUser'));
    }
}
