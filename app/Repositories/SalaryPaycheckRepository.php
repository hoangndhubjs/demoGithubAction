<?php
namespace App\Repositories;

use App\Models\SalaryPaychecks;
use Illuminate\Support\Facades\Auth;
use DateTime;
class SalaryPaycheckRepository extends Repository
{
    public function getModel(): string
    {
        return SalaryPaychecks::class;
    }

    public function detailSalaryUser($employee_id, $month){
        $date = DateTime::createFromFormat('m-Y', $month);
         if ($date){
             $month = $date->format('Y-m');
             $salary_detail = $this->model->with(['employeeSalary'])
                                           ->where('employee_id', $employee_id)->where('month',  $month)->first();
             return $salary_detail->toArray();
         }else{
             return array();
         }

    }

    public function checkDataMonth($employee_id, $month){
        if (request()->has('salary_paycheck_id')){
            $month = $month;
        }else{
            $date = DateTime::createFromFormat('d-m-Y', '01-'.$month);
            $month = $date->format('Y-m');
        }
//        $date = DateTime::createFromFormat('m-Y', $month);
        if ($month){

            $salary_detail = $this->model->with(['employeeSalary'])
                ->where('employee_id', $employee_id)->where('month',  $month)->first();
                // dd($salary_detail);
            return $salary_detail;
        }else{
            return array();
        }

    }
}
