<?php
namespace App\Repositories;

use App\Models\SalaryPayslip;
use Illuminate\Support\Facades\Auth;
use DateTime;
class SalaryPayslipsRepository extends Repository
{
    public function getModel(): string
    {
        return SalaryPayslip::class;
    }
    public function getPayslipUser($paginateConfig) {
        $user_info = Auth::user();
        $query = $this->model->where('employee_id', $user_info->user_id);
        $payslipsAll = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $payslipsAll;
    }
    public function detailSalaryUser($user_id, $month){
        $date = DateTime::createFromFormat('m-Y', $month);
         if ($date){
             $month = $date->format('Y-m');
             $salary_detail = $this->model->with(['employeeSalary','employeeCompany','department','designation','bankAccount'])
                                           ->where('employee_id', $user_id)->where('salary_month',  $month)->first();
             return $salary_detail->toArray();
         }else{
             return array();
         }

    }
    /**
     * chi tiết lương
     * same ^ have change
     */
    public function checkDataMonth($user_id, $month){
        if (request()->has('payslip_id')){
            $month = $month;
        }else{
            $date = DateTime::createFromFormat('d-m-Y', '01-'.$month);
            $month = $date->format('Y-m');
        }
//        $date = DateTime::createFromFormat('m-Y', $month);
        if ($month){

            $salary_detail = $this->model->with(['employeeSalary','employeeCompany','department','designation','bankAccount'])
                ->where('employee_id', $user_id)->where('salary_month',  $month)->first();
            return $salary_detail;
        }else{
            return array();
        }

    }

}
