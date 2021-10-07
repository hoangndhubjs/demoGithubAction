<?php
namespace App\Repositories;

use App\Classes\Settings\SettingManager;
use App\Models\AdvanceSalaries;
use App\Models\SalaryLoanDeduction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\EmployeeRepository;
use App\Models\SalaryStatutoryDeduction;
use App\Models\SalaryAllowance;
use App\Models\EmployeesPhepton;
use App\Models\MoneyMinus;
use Illuminate\Support\Facades\Log;

class CaculatorSalariesRepository extends Repository
{
    public function getModel(): string
    {
        return AdvanceSalaries::class;
    }
    public function getCalculatedPayslip($employee, $date) {

        $total_working_days = app()->make(EmployeeRepository::class)->getTotalWorkingDays($date, $employee);
        $checkSetTotalWorkings = (new DepartmentRepository())->setTotalWorkings($employee->department_id);
        if($checkSetTotalWorkings != false) {
            $total_working_days = $checkSetTotalWorkings;
        }
        $total_salary = 0;

        $temps_salary = (new EmployeeTmpPayslipRepository())->getTempSalary($employee->user_id, $date);
        $list_datcom_month = (new MealOrderRepository())->caculater_datcom($employee->user_id, $date);

        $total_late_minutes = 0;
        $total_leave_days = 0;
        $total_working_dates = 0;
        $total_leave_half_days = 0;
        $total_holidays = 0;
        $total_early_minutes = 0;
        $total_allowance_days = 0;
        $total_overtime = 0;
        $total_working_half_dates = 0;
        //User cans have many $temp_salary
        $payslip_data = array(
            'payslip_key' => $this->random_string('alnum', 40),
            'employee_id' => $employee->user_id,
            'department_id' => $employee->department_id,
            'company_id' => $employee->company_id ? $employee->company_id : 0,
            'designation_id' => $employee->designation_id,
            'salary_month' => $date,
            'wages_type' => $employee->wages_type,
            'basic_salary' => str_replace('.','',  $employee->basic_salary),
            'status' => 1,
            'total_commissions' => 0,
            'total_other_payments' => 0,
            'is_payment' => 0,
            'payslip_type' => 'full_monthly',
            'year_to_date' => date('d-m-Y'),
            'created_at' => date('Y-m-d H:i:s'),
            'total_working_days' => $total_working_days,
            'total_trial_working_days' => 0,
            'total_formal_working_days' => 0,
            'total_leave_full_days_trial' => 0,
            'total_leave_half_days_trial' => 0,
            'total_leave_full_days_formal' => 0,
            'total_leave_half_days_formal' => 0,
            'total_overtime_trial' => 0,
            'total_overtime_formal' => 0,
            'total_holidays_trail_work' => 0,
            'total_holidays' => 0
        );
        if (count($temps_salary) > 0) {
            foreach ($temps_salary as $temp_salary) {
                $is_chinh_thuc = $temp_salary->employee_type == 1;
                $is_thu_viec = $temp_salary->employee_type == 2;

                if ($is_thu_viec) {
                    $basic_salary = $employee->salary_trail_work;
                    $payslip_data['trail_salary'] = $basic_salary;
                    $payslip_data['total_trial_working_days'] = $temp_salary->ngay_cong;
                    $payslip_data['total_leave_full_days_trial'] = $temp_salary->ngay_nghi;
                    $payslip_data['total_leave_half_days_trial'] = $temp_salary->lam_nua_ngay;
                    $payslip_data['total_overtime_trial'] = $temp_salary->overtime;
                    $payslip_data['total_holidays_trail_work'] += $temp_salary->ngay_le;
                } else {
                    $basic_salary = str_replace('.', '', $employee->basic_salary);
                    $payslip_data['total_formal_working_days'] = $temp_salary->ngay_cong;
                    $payslip_data['total_leave_full_days_formal'] = $temp_salary->ngay_nghi;
                    $payslip_data['total_leave_half_days_formal'] = $temp_salary->lam_nua_ngay;
                    $payslip_data['total_overtime_formal'] = $temp_salary->overtime;
                    $payslip_data['total_holidays'] += $temp_salary->ngay_le;
                }

                $calculate_deductions = array(
                    'wages_type' => $temp_salary->employee_type,
                    'basic_price' => $basic_salary,
                    'date_works' => $temp_salary->ngay_cong,
                    'date_post' => $date,
                    'total_all_late_month' => $temp_salary->phut_di_muon,
                    'list_datcom_month' => 0,
                    'deduct' => $list_datcom_month['deduct'],
                    'receive_allowance' => $is_chinh_thuc ? $temp_salary->ngay_cong : 0,//$list_datcom_month['day_receive'],
                    'total_half_day' => $temp_salary->nghi_nua_ngay,
                    'day_off' => $temp_salary->ngay_nghi,
                    'total_working_days' => $total_working_days,
                    'holidays' => $temp_salary->ngay_le,
                    'overtime' => $temp_salary->overtime,
                    'total_working_half_dates' => $temp_salary->lam_nua_ngay,
                );
                $total_leave_days += $temp_salary->ngay_nghi;
                $total_leave_half_days += $temp_salary->nghi_nua_ngay;
                $total_late_minutes += $temp_salary->phut_di_muon;
                $total_working_dates += $temp_salary->ngay_cong;
                $total_working_half_dates += $temp_salary->lam_nua_ngay;
//                $total_holidays += $temp_salary->ngay_le;
                $total_overtime += $temp_salary->overtime;
                $total_early_minutes += $temp_salary->phut_ve_som;
                $total_allowance_days += $is_chinh_thuc ? floor($temp_salary->ngay_cong - ($temp_salary->ngay_nghi) - ($total_working_half_dates/2)) : 0;
                $current_salary = $this->salaryMonth($calculate_deductions);
                if ($is_thu_viec) {
                    $payslip_data['total_salary_trial'] = $current_salary;
                } else {
                    $payslip_data['total_salary_formal'] = $current_salary;
                }
                $total_salary += $current_salary;
            }
            //			$payslip_data['net_salary'] = $total_salary;
            $payslip_data['total_all_work_month'] = $total_working_dates;// + $total_working_half_dates/2; // số ngày công
            $payslip_data['total_all_late_month'] = $total_late_minutes; //tổng phut đi muộn trong tháng
            $payslip_data['total_all_leave_month'] = $total_early_minutes; //tổng phut giờ về sớm
            $payslip_data['total_leave_days'] = $total_leave_days + $total_leave_half_days/2; //tổng nghỉ phép có lương
            //$payslip_data['total_holidays'] = $total_holidays; //tổng ngày lễ
            $payslip_data['total_overtime'] = $total_overtime; //tổng ngày ot
            $payslip_data['total_day_datcom'] = $list_datcom_month['total_day_datcom'];
            $merge_half_day = $total_working_dates + $total_working_half_dates/2;
            //tien dat com
            $payslip_data['total_price_datcom'] = intval($list_datcom_month['price_datcom']);

            $leave_days = $total_working_days - $total_working_dates;
            $attendance_money = $this->getAttendanceMoney($leave_days, $total_late_minutes, $employee->user_id);
            $all_allowance = $this->getTotalAllowances($employee->user_id, $date); // các khoản phụ cấp của nhân viên được set
            $all_advance = $this->getTotalAdvanceByUser($employee->user_id, $date); // tiền tạm ứng lương
            $paid_leave = $this->getPaidLeaveByUser($employee->user_id, $date); // số phép tồn còn lại của nhân viên
            $total_minus_user = $this->getTotalMinus($employee->user_id, $date); // số tiền trừ của nhân viên


            $payslip_data['paid_leave'] = $paid_leave;
            $payslip_data['total_advance'] = $all_advance;
            $payslip_data['total_allowances'] = $this->getAllowanceOrderRice($total_allowance_days) + $all_allowance;
            $payslip_data['total_attendances'] = $attendance_money;
            $total_salary += $attendance_money;
            //Tien vay
            $loan = $this->getLoanAmount($employee->user_id);
            $payslip_data['total_loan'] = $loan;
            //Khau tru theo luat dinh (BH)
            $statutoryDeductions = $this->getStatutoryDeductions($employee->user_id);
            $payslip_data['total_statutory_deductions'] = $statutoryDeductions;
            # GROSS SALARY
            $total_salary = $total_salary - $statutoryDeductions;
            //Thue thu nhap ca nhan
            $income_tax = $this->getReducePersonal($employee->user_id, $total_salary);
            $payslip_data['saudi_gosi_amount'] = round($income_tax);
            // lương net = lương trước thuế - thuế tncn - tiền trừ(admin đặt) - tiền tạm ứng + phụ cấp không tính thuế
//            $net_salary = $total_salary - $income_tax - $total_minus_user + $payslip_data['total_allowances'];
            # Tong thu nhap sau khi da tru thue (NET)
            $gross_salary = $total_salary;
            $net_salary = $gross_salary - $all_advance - $income_tax - $loan - $payslip_data['total_price_datcom'] - $total_minus_user + $payslip_data['total_allowances'];
            $payslip_data['minus_money'] = $total_minus_user;
            $payslip_data['net_salary'] = round($gross_salary);
            # Tong thu nhap nhan duoc sau khi da tru di cac khoan vay (NET).
            $payslip_data['grand_net_salary'] = round($net_salary); //Tong thu nhap truoc thue;
//            $check = [
//                'luong trc thue' => $total_salary,
//                'thue tncn' => $income_tax,
//                'tien tru' => $total_minus_user,
//                'tien vay' => $loan,
//                'tien BH' => $statutoryDeductions,
//                'phu cap $' => $payslip_data['total_allowances'],
//                ' Tong thu nhap sau khi da tru thue ' => $net_salary,
//                'attena' => $attendance_money,
//                'total_price_datcom' => $payslip_data['total_price_datcom'],
//                'last'=>$total_salary - $all_advance,
//                '$current_salary' => $current_salary,
//                '$leave_days, $total_late_minutes' => $leave_days, $total_late_minutes
//            ];
//            dd($check);
        }
        return $payslip_data;
    }
    public function salaryMonth($calculate_deductions){

        $wages_type = $calculate_deductions['wages_type']; // loại lương
        $salary = str_replace('.', '', $calculate_deductions['basic_price']); // lương gốc
        $date_works = $calculate_deductions['date_works']; //
        $total_half_day_works = $calculate_deductions['total_working_half_dates'];
        $date_post = $calculate_deductions['date_post'];

        $late_in_month = $calculate_deductions['total_all_late_month'];
        $price_datcom = $calculate_deductions['list_datcom_month'];
        $deduct = $calculate_deductions['deduct'];
        $total_half_day = $calculate_deductions['total_half_day'];
        $day_off = $calculate_deductions['day_off'];
        $day_receive = $calculate_deductions['receive_allowance'];
        $total_day_wolks = $calculate_deductions['total_working_days'];
        $holidays = $calculate_deductions['holidays'];
        $overtime = $calculate_deductions['overtime'];

        $_300k_on_one_day = 300000/$total_day_wolks;
        $calulate_salary = ((intval($salary) / $total_day_wolks) - $_300k_on_one_day); // lương 1 ngày công
        $total_salary = $calulate_salary * $date_works; // tiền công/ngày * số ngày làm trong tháng
        $total_holiday = $holidays * $calulate_salary; // tiền nghỉ lễ
        $total_overtime = ($overtime/2) * $calulate_salary; // tiền overtime
        $total_salary_last = $total_salary + $total_holiday + $total_overtime - $price_datcom;
        return $total_salary_last < 0 ? 0 : $total_salary_last;
    }

    public function getAttendanceMoney($total_leave_days = 0, $total_late_minutes = 0, $employee_id) {
        $money = 0;
        $settingFullAllowance =  SettingManager::getOption('employees_full_attendance', []);
        if (in_array($employee_id, $settingFullAllowance) == true){
            return $money = 500000;
        }
        if ($total_leave_days > 4) {
            return $money;
        } elseif ($total_leave_days >= 1) {
            $money = 300000;
        } else {
            $money = 500000;
        }
        $penalty_times = $total_late_minutes - 300;
        if ($penalty_times > 0) {
            $subtract_money = $penalty_times * 1000;
            $money -= $subtract_money;
        }
        return ($money > 0) ? $money : 0;
    }
    // phụ cấp
    public function getAllowanceOrderRice($total_days = 0){
        $money = 25000;
        return $total_days * $money;
    }
    // 3: all loan/deductions
    public function getLoanAmount($employee_id){
        $salary_loan_deduction = SalaryLoanDeduction::where('employee_id', $employee_id)->get();
        $loan_de_amount = 0;
        if(isset($salary_loan_deduction)) {
            foreach($salary_loan_deduction as $sl_salary_loan_deduction){
                if($sl_salary_loan_deduction->status == 0 && $sl_salary_loan_deduction->is_deduction_salary == 1) {
                    $loan_de_amount += $sl_salary_loan_deduction->monthly_installment;
                }
            }
        }
        return $loan_de_amount;
    }
    //phép tồn
    public function getPaidLeaveByUser($user_id, $date){
        $month = Carbon::createFromFormat('Y-m', $date);
        $paid_leave = EmployeesPhepton::where('employee_id', $user_id)->where('year', $month->format('Y'))->first();
        return $paid_leave == null ? 0 : $paid_leave->remain_of_number;
    }
    //tiền trừ
    public function getTotalMinus($user_id, $date){
        return app(MoneyMinusRepository::class)->getTotalMinus($user_id, $date);
    }

    // statutory_deductions
    public function getStatutoryDeductions($employee_id){
        $statutory_deductions = SalaryStatutoryDeduction::where('employee_id', $employee_id)->get();
        $statutory_deductions_amount = 0;
        if(count($statutory_deductions) > 0) {
            foreach($statutory_deductions as $sl_salary_statutory_deductions){
                $statutory_deductions_amount += $sl_salary_statutory_deductions->deduction_amount;
            }
        }
        return $statutory_deductions_amount;
    }
    // thuế thu nhập cá nhân
    public function getReducePersonal($employee_id, $salary){
        $salary_reduce_pesonal =  config('constants.SALARY_REDUCE_PERSONAL');
        $salary_reduce_dependent =  config('constants.SALARY_REDUCE_DEPENDENT');
        $rule_income_tax = unserialize(config('constants.RULE_INCOME_TAX'));
        $countDependent = app()->make(EmployeeRepository::class)->count_contact_dependent($employee_id);
        $totalSalaryReduce = $salary_reduce_pesonal + $countDependent*$salary_reduce_dependent;
        $tax = 0;

        if($totalSalaryReduce < $salary){
            $check = $salary - $totalSalaryReduce;
            foreach ($rule_income_tax as $rule){
                if($check <= $rule['max']){
                    $tax = $check*$rule['percent']/100 - $rule['subtraction'];
                    break;
                }
            }
        }
        return $tax;
    }
    // SUM tiền phụ cấp theo user_id
    public function getTotalAllowances($user_id, $date){
        $allowance  = SalaryAllowance::where('employee_id', $user_id)->where('year_month', $date)->sum('allowance_amount');
        return $allowance ? $allowance : 0;
    }
    public function getTotalAdvanceByUser($user_id, $month){
        $advance  = $this->model->where('month_year', $month)->where('employee_id', $user_id)->where('status', 1)->sum('advance_amount');
        return $advance;
    }

    /**
     * Create a "Random" String
     *
     * @param	string	type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
     * @param	int	number of characters
     * @return	string
     */
    protected function random_string($type = 'alnum', $len = 8)
    {
        switch ($type)
        {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type)
                {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique': // todo: remove in 3.1+
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt': // todo: remove in 3.1+
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
}

}
