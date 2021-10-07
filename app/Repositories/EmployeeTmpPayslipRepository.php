<?php
namespace App\Repositories;

use App\Models\AttendanceDaily;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeTmpPayslip;
use App\Models\Leave;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Psr7\str;

class EmployeeTmpPayslipRepository extends Repository
{
    public function getModel(): string
    {
        return EmployeeTmpPayslip::class;
    }

//    public function getTmpPayslipAt($month, $user_id, $)
    public function getTempSalary($user_id, $date_post) {
        $query = $this->model->where('employee_id', $user_id)->where('month', $date_post)->get();
        return $query;
    }

    public function getTimeSheetByMonth($paginateConfig, $request){
        $month = date('Y-m');

        if(isset($request->month)){
            $month = date('Y-m', strtotime('01-'.$request->month));
        }

        $query = $this->model->selectRaw('*, SUM(ngay_cong) as total_ngaycong, SUM(phut_di_muon) as total_phut_di_muon, SUM(phut_ve_som) as total_phut_ve_som, SUM(lam_nua_ngay) as total_lam_nua_ngay')
            ->with(['employee'])
            ->where('month', $month);

        if (isset($request->employee_name)){
            $query->whereHas('employee', function($q) use ($request){
                $q->whereRaw("concat(first_name, ' ', last_name) like '%$request->employee_name%' ");
                $q->orWhereRaw("concat(last_name, ' ', first_name) like '%$request->employee_name%' ");
                $q->orWhere("employee_id", "like", '%'.$request->employee_name.'%');
            });
        }
        $query->groupBy('employee_id');

        $listStaff = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listStaff;
    }

    public function getInformationTimeSheetByMonth($month, $employeeIsActive) {

        $payslip_data = [];
        foreach ($employeeIsActive as $val) {

            $temps_salary = EmployeeTmpPayslip::selectRaw('SUM(ngay_cong) as total_ngaycong, SUM(phut_di_muon) as total_phut_di_muon, SUM(phut_ve_som) as total_phut_ve_som, SUM(lam_nua_ngay) as total_lam_nua_ngay, SUM(ngay_nghi) as total_ngay_nghi, SUM(ngay_le) as total_ngay_le, SUM(nghi_nua_ngay) as total_nghi_nua_ngay' )
                ->where('employee_id', $val->user_id)
                ->where('month', $month)
                ->groupBy('employee_id')
                ->first();

            $employee_name = $val->first_name .' '. $val->last_name;
            $user_id = $val->user_id;
            $department_id = $val->department_id ?? 0;
            $company_id = $val->company_id ?? 0;
            $employee_id = $val->employee_id;
            $early_leave = $temps_salary ? $temps_salary->total_phut_ve_som : '0';
            $come_late = $temps_salary ? $temps_salary->total_phut_di_muon : '0';

            $total_attendance = $temps_salary->total_ngaycong ?? 0;
            $total_ngay_le = $temps_salary->total_ngay_le ?? 0;
            $lam_nua_ngay = $temps_salary && $temps_salary->total_lam_nua_ngay ? $temps_salary->total_lam_nua_ngay/2 : 0;
            $nghi_nua_ngay = $temps_salary && $temps_salary->total_nghi_nua_ngay ? $temps_salary->total_nghi_nua_ngay/2 : 0;
            $ngay_nghi = $temps_salary ? $temps_salary->total_ngay_nghi : 0;

            $ngay_co_phu_cap = $total_attendance - $ngay_nghi - $lam_nua_ngay - $nghi_nua_ngay;

            $totalPhepTon = (new EmployeesPheptonRepository())->checkPhepTon(['employee_id' => $user_id, 'leave_type_id'=>Leave::LEAVE_TYPE_PHEP_TON, 'year'=>date('Y')]);
            $payslip_data[] = [
                'id' => $user_id,
                'department_id' => $department_id,
                'company_id' => $company_id,
                'employee_name' => $employee_name,
                'employee_id' => $employee_id,
                'early_leave' => $early_leave,
                'come_late' => $come_late,
                'total_attendance' => $total_attendance+$total_ngay_le.'',
                'total_allowances' => $ngay_co_phu_cap.'',
                'phep_ton' => (isset($totalPhepTon)) ? $totalPhepTon->remain_of_number.'' : '0',
            ];
        }

        return $payslip_data;

    }

    public function getTotalEmployee(){
        $month = date('Y-m');

        $early_leave = EmployeeTmpPayslip::where('month', $month)->where('phut_di_muon','>=', 300)->groupBy('employee_id')->get()->count();
        $come_late = EmployeeTmpPayslip::where('month', $month)->where('phut_ve_som','>=', 180)->groupBy('employee_id')->get()->count();

        return ['di_muon' => $early_leave, 've_som' => $come_late];
    }

    public function leaveApplicationByMonth() {
        $form_date = date('Y-m-01');
        $to_date = date('Y-m-t');

        $leaveApplications = LeaveApplication::where('company_id', 1)
            ->where(function($query) use ($form_date, $to_date) {
                $query->orWhere([
                    ['from_date', '=<', $form_date],
                    ['to_date', '>=', $to_date],
                ])
                    ->orWhereBetween('from_date',[$form_date,$to_date])
                    ->orWhereBetween('to_date', [$form_date,$to_date]);
            })->get();

        $countLeaveApplications = $leaveApplications->count();
        $approved = 0;
        $reject = 0;
        $waiting = 0;
        $data = [];
        foreach ($leaveApplications as $val) {
            if ($val->confirm == 1){
                $approved++;
            } else if ($val->status == 3) {
                $reject++;
            } else {
                $waiting++;
            }
            $data = ['approved' => $approved, 'reject'=>$reject, 'waiting' => $waiting];
        }

        return ['totalLeaveApplication' => $countLeaveApplications, 'status' => $data];
    }

    public function employeeLateMost($employeeActive) {

        $employeeLateMost = $this->getInformationTimeSheetByMonth(date('Y-m'), $employeeActive);

        foreach ($employeeLateMost as $index => $val) {
            $department = Department::where('department_id', $val['department_id'])->first();
            $department_name = $department ? $department->department_name : 'Không xác định';
            $company = Company::where('company_id', $val['company_id'])->first();
            $company_name = $company ? $company->name : 'Không xác định';
            $employeeLateMost[$index]['department_name'] = $department_name;
            $employeeLateMost[$index]['company_name'] = $company_name;
        }
        $collection = collect($employeeLateMost);
        $sorted = $collection->sortByDesc('come_late');
        $employeeLateMostSort = $sorted->values()->all();

        return $employeeLateMostSort;
    }

    public function totalQuitWorkMost($employeeActive) {

        $form_date = date('Y-m-01');
        $to_date = date('Y-m-t');

        $totalQuitMost = [];

        foreach ($employeeActive as $key => $val) {

            $attendance_daily = AttendanceDaily::selectRaw('SUM(total_request_leave_full) + (SUM(total_request_leave_half)/2) + SUM(total_request_leave_full_no_salary) + (SUM(total_request_leave_half_no_salary)/2) as total_leave')
                ->where('employee_id', $val->user_id)
                ->whereBetween('day', [$form_date, $to_date])
                ->groupBy('employee_id')
                ->first();
            $result = isset($attendance_daily) ? rtrim($attendance_daily->total_leave, 0) : 0;
            $employee_name = $val->first_name .' '. $val->last_name;;
            $quitMost = rtrim($result, ".");
            $department = Department::where('department_id', $val->department_id)->first();
            $department_name = $department ? $department->department_name : 'Không xác định';
            $company = Company::where('company_id', $val->company_id)->first();
            $company_name = $company ? $company->name : 'Không xác định';
            $totalQuitMost[] = [
                'id' => $val->user_id,
                'employee_name' => $employee_name,
                'total_day_off' => $quitMost,
                'department_name' => $department_name,
                'company_name' => $company_name
            ];
        }
        $collection = collect($totalQuitMost);
        $sorted = $collection->sortByDesc('total_day_off');
        $totalQuitMostSort = $sorted->values()->all();

        return $totalQuitMostSort;
    }
}
