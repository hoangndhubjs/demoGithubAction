<?php
namespace App\Repositories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\MealOrder;
use App\Models\EmployeeTmpPayslip;
use App\Models\SalaryPayslip;
use Auth;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class DashboardRepository
{
    // in lời chào theo thời gian trong ngày
    public function getGreetingByTime()
    {
        $hour = date("H");

        if ($hour >= 5 && $hour < 12) {
            return __('good_morning');
        }
        elseif ($hour >= 12 && $hour < 18) {
          return __('good_afternoon');
        }
        elseif ($hour >= 18 && $hour < 22) {
            return __('good_evening');
        }
        else {
          return __('good_night');
        }

    }

    //đếm tổng số lần đặt cơm trong tháng
    public function countOrderMeal()
    {
        $startMonth = date('Y-m').'-01';
        $endMonth = date("Y-m-t");
        $user_id = Auth::id();

        $query = MealOrder::where('user_id', $user_id)
                        ->whereBetween('create_date', [$startMonth, $endMonth])
                        ->where('status', 1)
                        ->count();
        return $query;
    }

    public function queryEmployeeTmpPayslip()
    {
        $currentMonth = date('Y-m');
        $user_id = Auth::id();
        $query = EmployeeTmpPayslip::where('employee_id', $user_id)->where('month', $currentMonth);

        return $query;
    }

    //lấy tổng số phút đi muộn về sớm trong tháng
    public function getMinuteLateLeaveEarly()
    {
        $query = $this->queryEmployeeTmpPayslip();

        $result = $query->selectRaw('sum(phut_di_muon) as min_late, sum(phut_ve_som) as min_soon')->get();

        return $result;
    }

    //lấy số ngày công (nừa ngày, 1 ngày)
    public function getTotalWorkingDayInMonth()
    {
        $query = $this->queryEmployeeTmpPayslip();

        $result = $query->selectRaw('sum(ngay_cong) as full_working, sum(lam_nua_ngay) as half_working, sum(ngay_le) as holiday, 
        sum(ngay_nghi) as ngay_nghi, sum(nghi_nua_ngay) as nghi_nua_ngay')->first();

        return $result;
    }

    //lấy số ngày nghỉ
    public function getTotalDayOffInMonth()
    {
        $query = $this->queryEmployeeTmpPayslip();

        $result = $query->selectRaw('sum(ngay_nghi) as day_off, sum(nghi_nua_ngay) as half_day_off')->get();

        $day_off = $result[0]->day_off + ($result[0]->half_day_off/2);

        return $day_off;
    }

    //lấy tổng số ngày công cần làm trong một tháng trừ ngày chủ nhật
    function getTotaWorkdayToDoInMonth()
    {
        $sundays=0;
        $month = date('m');
        $year = date('Y');

        $total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for($i=1;$i<=$total_days;$i++) {
            if(date('N',strtotime($year.'-'.$month.'-'.$i))==7){
                $sundays++;
            }
        }
        return $total_days-$sundays;
    }

    public function getEmployeeIsActive() {
        return Employee::where('is_active', 1)->count();
    }

    // tong so nhan vien xin  nghi ngay hom nay
    function getEmployeeExitToday() {
        $toDay = date("Y-m-d");

        $query = LeaveApplication::where('from_date', '>=', $toDay)->where('to_date', '<=', $toDay)->count();

        return $query;
    }

    //bien che luong theo thang
    public function payrollByMonth(){
        $payroll = [];

        foreach (CarbonPeriod::create(now()->subMonths(12), '1 month', now()->subMonths(1)) as $date) {
         $date = $date->format("Y-m");
         $month[] = date('m-Y', strtotime($date));
         $payroll[] = SalaryPayslip::where('company_id', 1)->where('salary_month', $date)->sum('basic_salary');

        }

        return [$month, $payroll];
    }

    // bien che luong theo bo phan
    public function payrollByDepartment() {
        $department = Department::where('company_id', 1)->get();
        $this_month = date('Y-m', strtotime('-1 month'));
        $last_month = date('Y-m', strtotime('-2 month'));
        $payroll = [];
        foreach ($department as $key => $value) {
            if ($value->department_id == 11) {
                continue;
            } else {
                $payroll['name'][] = $value->department_name;
                $payroll['this_month'][] = SalaryPayslip::where('company_id', 1)->where('salary_month', $this_month)->where('department_id', $value->department_id)->sum('basic_salary');
                $payroll['last_month'][] = SalaryPayslip::where('company_id', 1)->where('salary_month', $last_month)->where('department_id', $value->department_id)->sum('basic_salary');
            }
        }
        return $payroll;
    }

    //so nhan vien theo bo phan
    public function employeeActiveDepartment() {
        $department = Department::where('company_id', 1)->get();
        $employeeDepartment = [];
        $totalEmployeeDepartment = [];
        foreach ($department as $key => $value) {
            $sql = Employee::where('company_id', 1)->where('is_active', 1)->where('department_id', $value->department_id)->count();

            $employeeDepartment_id = [];
            $employeeDepartment_id['name'] = $value->department_name;
            $employeeDepartment_id['total'] = $sql;
            $employeeDepartment[] = $employeeDepartment_id;

            $totalEmployeeDepartment[] = $sql;
        }

        return [$employeeDepartment, $totalEmployeeDepartment];
    }
}
