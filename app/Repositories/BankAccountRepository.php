<?php
namespace App\Repositories;

use App\Models\EmployeeBankaccount;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeTmpPayslip;

class BankAccountRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeBankaccount::class;
    }

    public function getBankAccount($paginateConfig, $user_id) {

        $query = EmployeeBankaccount::where('employee_id', $user_id);

        $listBankAccount = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listBankAccount;
    }
    public function getBankAccountByUser($user_id, $date){
        $checkUserWokings = EmployeeTmpPayslip::with('employee')
                                                ->where('employee_id', $user_id)
                                                ->where('month', $date)
                                                ->get();
//        dd();
        $salary_advanced = app()->make(AdvanceSalariesRepository::class)->getAdvancedUser($user_id, $date);
        if (count($checkUserWokings) > 0){
            $salary_day = 0;
            $money_can_loan = 0;
            foreach ($checkUserWokings as $woking){
                $workingDays = $woking->ngay_cong;
                $total_working_days = app()->make(EmployeeRepository::class)->getTotalWorkingDays($date, $woking->employee);
                // so ngay cua quanplay dc set
                $checkSetTotalWorkings = (new DepartmentRepository())->setTotalWorkings($woking->employee->department_id);
                if($checkSetTotalWorkings != false) {
                    $total_working_days = $checkSetTotalWorkings;
                }
                $ways_type = $woking->employee_type;
                if ($ways_type == 2){
                    $salaryUser = str_replace(".", "", trim($woking->employee->salary_trail_work));
                }else{
                    $salaryUser = str_replace(".", "", trim($woking->employee->basic_salary));
                }
                $salary_day = round(floor($salaryUser/$total_working_days));// * 0.5;
                $money_can_loan += $salary_day * ($workingDays/2);
            }
        }else{
            $money_can_loan = 0;
        }
        $bankAccount = EmployeeBankaccount::with('employee')->where('employee_id', $user_id)->first();
            $arr = array(
                'bank_name' => $bankAccount ? $bankAccount->bank_name : '',
                'account_number' => $bankAccount ? $bankAccount->account_number : '',
            );
        $arr['salary_advance'] = round($money_can_loan - $salary_advanced['total']);
        $arr['salary_advanced_amounted'] = $salary_advanced['list_advance'];
//        dd($arr);
        return $arr;
    }
}
