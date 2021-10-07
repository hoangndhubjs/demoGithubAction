<?php
namespace App\Repositories;

use App\Models\Employee;
use App\Models\SalaryPayslip;
use App\Repositories\CaculatorSalariesRepository;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AdvanceSalaries;
use App\Repositories\MealOrderRepository;
use App\Models\SalaryLoanDeduction;
use App\Repositories\EmployeeTmpPayslipRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use App\Models\MoneyMinus;
class SalaryManagerRepository extends Repository
{
    public function getModel(): string
    {
        return SalaryPayslip::class;
    }

    /**
     * kiểm tra data theo tháng
     */
    public function check_exits_data($month){
        $query = $this->model->where('salary_month', 'like' ,'%'.$month.'%')->get();
        return count($query);
    }
    public function getMaxMinSalaryUser(){
       $user_info = Auth::user();
       $company_id = $user_info->company_id;
       $date_before = date("Y-m", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . "-1 month"));
       $salary_all = $this->model->where('company_id', $company_id)->where('salary_month', $date_before)->get();
        if (count($salary_all) == 0){
            return array();
        }
       $basics = array();
       $field_find_max = 0;
       $field_find_min = 0;
       $total_salary = 0;
       $user_id_max= 0;
       $user_id_min= 0;

          foreach ($salary_all as  $basic_salary){
              $basics[] = str_replace(".","", $basic_salary->grand_net_salary);
              $total_salary += str_replace(".","", $basic_salary->grand_net_salary);
              if ($basic_salary->grand_net_salary < $field_find_min){
                  $field_find_min = $basic_salary->grand_net_salary;
                  $user_id_min = $basic_salary->employee_id;
              }
              if ($basic_salary->grand_net_salary > $field_find_max){
                  $field_find_max = $basic_salary->grand_net_salary;
                  $user_id_max = $basic_salary->employee_id;
              }
          }
           $field_find_avg = array_sum($basics) / count($basics);
//
//       $user_info_max = Employee::with(['department','company','designation'])->find($user_id_max);
//       $user_info_min = Employee::with(['department','company','designation'])->find($user_id_min);

       $max_min = [$user_id_max, $user_id_min];
       $info_array = [];
       foreach ($max_min as $info){
           $user_info = Employee::with(['department','company','designation'])->find($info);
           $info_array[] = array(
               'name' => $user_info->first_name.' '.$user_info->last_name,
               'departmen' => $user_info->department != null ? $user_info->department->department_name : '',
               'company' => $user_info->company->name,
               'position' => $user_info->designation != null ? $user_info->designation->designation_name : ''
           );
       }

       $getBySalary = array(
           'max_salary' => ['salary'=>$field_find_max,'info'=>$info_array[0]],
           'min_salary' => ['salary'=>$field_find_min,'info'=>$info_array[1]],
           'avg_salary' => floor($field_find_avg),
           'total_salary' => $total_salary,
       );

      return $getBySalary;
   }
   public function chartSalary(){
       $user_info = Auth::user();
       $company_id = $user_info->company_id;
       $department = Department::where('company_id', $company_id)->get();
       $last_month = date('Y-m', strtotime('-1 month'));
       $payroll = [];
       foreach ($department as $key => $value) {
           if ($value->department_id == 11) {
               continue;
           } else {
               $payroll['name'][] = $value->department_name;
               $payroll['last_month'][] = $this->model->where('company_id', $company_id)
                   ->where('salary_month', $last_month)
                   ->where('department_id', $value->department_id)
                   ->sum('grand_net_salary');
               $payroll['average'][] = $this->model->where('company_id', $company_id)
                   ->where('salary_month', $last_month)
                   ->where('department_id', $value->department_id)
                   ->avg('grand_net_salary');
           }
       }
       return $payroll;
   }
    public function checkDataInMonth($month){
       return $this->model->where('salary_month', $month)->get();
    }
    public function getListPayroll($paginateConfig, $request) {
        $employee_model = new Employee();
        $user_info = Auth::user();
        $company_id = $request->company_id;
        $status = $request->status_payslip;
        $department = $request->department_id;
//        $date = Carbon::createFromFormat("m-Y", $request->month_payslip);
        $salary_month = $request->month_payslip;

        $query = $this->model->with(['employeeSalary','bankAccount','employeeCompany']);

        if ($request->history_payroll){ //type module history_payrolll
            if ($request->employee__){
                $query->where('employee_id', $request->employee__);
            }
            $query->where('status', $status ? $status : 2);

        }elseif($status){ # module history
            $query->where('status', $status);
        }

        if ($company_id){
            $query->where($this->model->qualifyColumn('company_id'), $company_id);
        }
       if ($department){
            $query->where($this->model->qualifyColumn('department_id'), $department);
        }
       if ($salary_month){
           $convert_date = Carbon::createFromFormat('m-Y', $salary_month)->format('Y-m');
           $query->where('salary_month', $convert_date);
       }
//        dd($query->get(), $convert_date);
//        if ($column = $paginateConfig->getSortColumn()) {
//            $query->orderByDesc($column,  $paginateConfig->getSortDir());
//        }
        $query->leftJoin('employees as xe', 'xe.user_id', '=', 'salary_payslips.employee_id')->orderBy('xe.employee_id', 'ASC');
        $query->selectRaw("xin_xe.employee_id as employee_code, xin_salary_payslips.*");
//        $query->orderBy('employees.employee_id', 'ASC');

        $payslip = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $payslip;
    }
    //update payrolls

    /**
     * update 1 hoặc nhiều cá nhân
     * status = 1 => cập nhật tất cả trong tháng
     * status = 2 => cập nhật 1 nhân viên
     * payslipId_arr update theo list id checkbox
     */
    public function updatePayments($request){

//        $dates_request = Carbon::createFromFormat('m-Y', $request->month_salary);
        $date = $request->month_salary;
//            $dates_request->format('Y-m');


        $payslip_query = $this->model->where('salary_month', $date);
        $pay_employees = $request->get('payslipId_arr', []);
        if ($paySlipId = $request->payslip_id) {
            $pay_employees[] = $paySlipId;
        }
        Log::info(sprintf('Starting update payslip for [%s]', implode(', ', $pay_employees)));
        if (is_array($pay_employees) && count($pay_employees)  > 0 ) {
            $payslip_query->whereIn('payslip_id', $pay_employees);
        }
        if ($request->status == 1 || $request->status == 2) {
            $payslips = $payslip_query->get();
            foreach($payslips as $payslip) {
                Log::info("Updating Payslip [$payslip->id]");
                if ($request->status == 2 && $payslip->status != 2){
                    $delete_minus =  $this->minus_money($payslip->employee_id, $date);
                    if($delete_minus == false){
                        DB::rollBack();
                        return false;
                    }
                }
                $data = [
                    'status' => 2
                ];
                try {
                    $payslip->status != 2 && $payslip->update($data);
                } catch (\Exception $e) {
                    Log::error("Update Payslip [$payslip->id] ERROR", ['exception' => $e]);
                    return false;
                }

                Log::info("Updated Payslip [$payslip->id]");
            }
            Log::info(sprintf('Updated payslip for [%s]', implode(', ', $pay_employees)));
            return true;
        }
        return false;
    }
    /**
     * xóa các khoản được set == 1 => tiền trừ tháng hiện tại
     *
     */
    protected function minus_money($user_id,$date){
        Log::info("deleting minus user_id =>  [$user_id]");
        // option == 1 đã thanh toán (không khấu trừ vào tháng sau)
        $data = [
            'user_id' => $user_id,
            'option_minus' => 1,
            'amount_option' => 1
        ];
        try {
            DB::beginTransaction();
            MoneyMinus::where('year_month',$date)->update($data);
            DB::commit();
            Log::info("deleted minus user_id =>  [$user_id]");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    //    advance_money
    public function advance_money($paginateConfig, $request, $user_id = null){
        $query  = AdvanceSalaries::with(['employeeAsset','companyAsset','bankAccount'])->where('company_id', Auth::user()->company_id);
        $query_string = $request->query_string;
        if ($query_string != ''){
            $users = Employee::whereRaw("concat(first_name, ' ', last_name) like '%$query_string%' ")
                ->orWhereRaw("concat(last_name, ' ', first_name) like '%$query_string%' ")->get();
            $users_id = [];
            foreach ($users as $user_id){
                $users_id[] = $user_id->user_id;
            }
          $query->whereIn("employee_id", $users_id);

        }
        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        if(!Auth::user()->isAdmin()){
            $query->where("employee_id", Auth::user()->user_id);
        }
        if($request->month != ""){
            $date = Carbon::createFromFormat('m-Y', $request->month)->format('Y-m');
            $query->where("month_year", $date);
        }
        $advance = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $advance;
    }
    // salary tt
    public function payroll_salary_all($request){
        $company_id = $request->company_id;
//        $month_salary = Carbon::createFromFormat('m-Y' ,$request->month_salary);
        $month = $request->month_salary;
        $dateNow = date("Y-m");
        $dayNow = date('d');
        $dateInMonth = date('t', strtotime($dateNow));
        $date_post = $month;
        $countDaySalary = $dateInMonth - $dayNow + 1;
        $today = strtotime($date_post);
        $day_td = strtotime($dateNow);

        if ($today > $day_td) {
            $request_date_post = 'Kiểm tra lại năm và tháng cần thanh toán';
            $array_alert = array(
                'status' => false,
                'title_reponse' => $request_date_post,
            );
            return 	$this->output($array_alert);
        } else if ($today == $day_td) {
            //ngày từ giờ đến cuối tháng
            $request_date_post_i = 'Còn ' . $countDaySalary  . ' ngày để tạo phiếu lương';
            $array_alert = array(
                'status' => false,
                'title_reponse' => $request_date_post_i,
            );
            return $this->output($array_alert);
        }
        $employees = app()->make(EmployeeRepository::class)->getWorkingEmployeesInMonth($request);
        try {
            DB::transaction(function () use($employees, $date_post) {
                foreach ($employees as $employee) {
                    $payslip_in_month = $this->getPayslipOf($employee->user_id, $date_post);
                    if ($payslip_in_month) {
                        if ($payslip_in_month->is_payment == 0 && $payslip_in_month->status == 1) {
                            $payslip = app()->make(CaculatorSalariesRepository::class)->getCalculatedPayslip($employee, $date_post);
                            unset($payslip['payslip_key']);
                            $payslip_in_month->update($payslip);
                        }
                        //                    $this->model->where('payslip_key', $key)->update($payslip);
                    } else {
                        //Only allow paid for employee don't have payslip;
                        $payslip = app()->make(CaculatorSalariesRepository::class)->getCalculatedPayslip($employee, $date_post);
                        $this->model->create($payslip);
                    }
                }
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        $is_sucees = array(
            'status' => 'success',
            'mess' => 'success',
        );
        return response()->json($is_sucees);
    }
    public function getPayslipOf($employee_id, $month) {
        return $this->model->where('employee_id', $employee_id)->where('salary_month', $month)->first();
    }
    protected function output($return){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/

		return response()->json($return);
	}
}
