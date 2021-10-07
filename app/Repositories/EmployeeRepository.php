<?php
namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeBankaccount;
use App\Models\EmployeeContact;
use App\Models\EmployeeImmigration;
use App\Models\EmployeeWorkExperience;
use App\Models\Role;
use App\Models\SalaryAllowance;
use App\Models\EmployeeTmpPayslip;
use App\Models\SalaryStatutoryDeduction;
use App\Models\SalaryOtherPayment;
use App\Models\SalaryLoanDeduction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class EmployeeRepository extends Repository
{


    public function getModel(): string
    {
        return Employee::class;
    }

    public function getEmployee($employee_id) {
        return $this->model->find($employee_id);
    }
    public function getEmployeesByCompany($company_id)
    {
        $is_active = 1; // nhân viên active
        $query = Employee::where('company_id', $company_id)->where('is_active', $is_active)->get();
        return $query;
    }
    public function getUserIdByCompany($user_id, $company_id, $department_id)
    {
        $is_active = 1; // nhân viên active
        $query = Employee::where('company_id', $company_id)->where('is_active', $is_active);
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        return $query->get();
    }
    public function getUserByCompnayId($company_id){
        $query = Employee::where('company_id', $company_id)->get();
        return $query;
    }

    public function getListRole($company_id){
        return Role::where('company_id', $company_id)->pluck('name', 'id');
    }
    public function getUserByDeparment($department_id){
        $query = Employee::where('department_id', $department_id)->get();
        return $query;
    }
    public function getBirthdayCommingEmployee($startDate, $endDate){
//        $employees_birthday = Employee::selectRaw('user_id, first_name, last_name, date_of_birth, DATE_ADD( date_of_birth,
//            INTERVAL IF ( DAYOFYEAR( date_of_birth ) >= DAYOFYEAR( CURDATE( ) ), YEAR ( CURDATE( ) ) - YEAR ( date_of_birth ),
//            YEAR ( CURDATE( ) ) - YEAR (  date_of_birth ) + 1 ) YEAR  ) AS next_birthday');
         $employees_birthday = Employee::selectRaw('user_id, first_name, last_name, date_of_birth, DATE(CONCAT(YEAR(CURDATE()),"-",MONTH(date_of_birth),"-",DAY(date_of_birth))) as birth_day');
         $employees_birthday->whereNotNull('date_of_birth')
            ->where('is_active',1)
             ->whereRaw('DATE(CONCAT(YEAR(CURDATE()),"-",MONTH(date_of_birth),"-",DAY(date_of_birth))) >= "'.$startDate.'"')
             ->whereRaw('DATE(CONCAT(YEAR(CURDATE()),"-",MONTH(date_of_birth),"-",DAY(date_of_birth))) <= "'.$endDate.'"')
            ->orderBy('date_of_birth', 'desc');
        $list_birth_day =[];
        foreach ($employees_birthday->get() as $employees_id){
            $name = $employees_id->last_name .' '. $employees_id->first_name;
            $list_birth_day[] = [
                'title'=> $name.' - '.__('xin_hr_calendar_upc_birthday'),
				'start'=> $employees_id->birth_day,
                'description' => __('xin_hr_calendar_employee_birthday').' '.$name,
                'unq'=> 2,
                'className'=> "fc-event-light fc-event-solid-warning",
            ];
        }
    return $list_birth_day;
    }
    // function calculator salary
    public  function getWorkingEmployeesInMonth($data) {
        $employeeTmp = new EmployeeTmpPayslip();
        $employee_id = $data->employee_id;
        $company_id = $data->company_id;
        $listBotToArray = explode(',', config('constants.BOD_USER_IDS'));

//        $month_salary = Carbon::createFromFormat('m-Y' ,$data->month_salary);
        $month = $data->month_salary;
        $query = $this->model->whereIn('user_id', function($fillter) use ($month, $employeeTmp, $listBotToArray) {
            $fillter->select(DB::raw('employee_id'))
            ->from($employeeTmp->getTable())
            ->where('month', $month);//->whereNotIn('employee_id', $listBotToArray);
        })->whereNotNull('office_shift_id');
    	if ($employee_id) {
            $query->where('user_id', $employee_id);
        }
    	return $query->get();
	}
    /**
     * get user against
     * @param $month
     * @param Employee $employee
     * @return mixed
     */
    public function getTotalWorkingDays($month, Employee $employee) {

        $date = Carbon::createFromFormat('Y-m-d', $month.'-01');
        $office_shift = $employee->office_shift;
        $last_day = $date->format('t');
        $start_day = 1;
        $total_working_days = 0;
        for($i = $start_day; $i <= $last_day; $i++) {
            $_date = Carbon::createFromFormat('Y-m-j', $date->format('Y-m-').$i);
            $day_name = $_date->englishDayOfWeek;
            $column_time_in = sprintf("%s_in_time", strtolower($day_name));
            $office_shift->$column_time_in && $total_working_days++;
//            if (!($_date->format('D') === "Sun")) {
//                $total_working_days++;
//            }
        }
        return $total_working_days;
    }
    // get contacts dependent by employee_id
    public function count_contact_dependent($id) {
        $count_contact = EmployeeContact::selectRaw('COUNT(employee_id) as total')->where('employee_id', $id)->where('is_dependent', 2)->limit(1)->get();
        return $count_contact[0]->total;
    }

    /**
     * get user against
     *
     * @param $ids
     * @return mixed
     */
    public function getAgainstByIds($ids){
        return $this->model->whereIn('user_id', $ids)->get();
    }

    public function getNameEmployeesByCompany($company_id)
    {
        $is_active = 1; // nhân viên active
        $query = $this->model->select("user_id as id", DB::raw('CONCAT(first_name,\' \', last_name) AS fullname'))
            ->where('company_id', $company_id)
            ->where('is_active', $is_active)
            ->pluck('fullname', 'id');
        return $query;
    }
    public function getNameEmployeesfillter($query_string)
    {
        $users = $this->model->whereRaw("concat(first_name, ' ', last_name) like '%$query_string%' ")
            ->orWhereRaw("concat(last_name, ' ', first_name) like '%$query_string%' ")->get();
        $users_id = [];
        foreach ($users as $user_id){
            $users_id[] = $user_id->user_id;

        }
        return $users_id;
    }
    public function getActiveEmployees($companyId) {
        return $this->model->where([
            'is_active' => Employee::STATUS_ACTIVE,
            'company_id' => $companyId
        ])->get();
    }

    public function getEmployeeIds($companyId) {
        return $this->getActiveEmployees($companyId)->pluck('user_id')->toArray();
    }

    /**
     * danh sach nhan vien chinh thuc
     *
     * @param $company_id
     * @return mixed
     */
    public function officialEmployee($company_id){
        $is_active = 1; // nhân viên active
        $wages_type = 1; // nhân viên chính thức
        $result = Employee::where('company_id', $company_id)
            ->where('is_active', $is_active)
            ->where('wages_type', $wages_type)
            ->get();
        return $result;
    }

    public function listStaffbyCompany($paginateConfig, $request){
        $query = $this->model->with(['designation','department','office_shift','company','report_to', 'role'])
            ->where('company_id', Auth::user()->company_id);
        if ($request->department_id){
            $query->where('department_id', $request->department_id);
        }
        if ($request->is_active != ''){
            $query->where('is_active', $request->is_active);
        }
        if($request->role != ''){
            $query->where('user_role_id', $request->role);
        }
        if ($request->employee_name){
            $query->where(function($q) use ($request){
                $q->whereRaw("concat(first_name, ' ', last_name) like '%$request->employee_name%' ");
                $q->orWhereRaw("concat(last_name, ' ', first_name) like '%$request->employee_name%' ");
                $q->orWhere("employee_id", "like", '%'.$request->employee_name.'%');
            });
        }
        $query->orderBy('employee_id', 'ASC');
        $listStaff = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listStaff;
    }
    /**
     */
    public function listDatatable($paginateConfig, $user_id = null, $type = null)
    {
        if (!$user_id){
            return array();
        }

        if ($type == 'deductions'){ // request deductions
            $query = SalaryStatutoryDeduction::where('employee_id', $user_id)->orderBy('statutory_deductions_id','desc');
        }else if($type == 'otherPayment'){
            $query = SalaryOtherPayment::where('employee_id', $user_id)->orderBy('other_payments_id','desc');
        }elseif($type =='loan_deductions'){
            $query = SalaryLoanDeduction::where('employee_id', $user_id)->orderBy('loan_deduction_id','desc');
        }else{
            $query = SalaryAllowance::where('employee_id', $user_id)->orderBy('allowance_id','desc');
        }
        $allowance = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $allowance;
    }
    // allowance staff
    public function findAllowance($id){
        return SalaryAllowance::find($id);
    }
    public function findDataModule($id, $module){
        if ($module == 'deductions'){
            $find_ = SalaryStatutoryDeduction::find($id);
        }elseif($module == 'otherPayment'){
            $find_ = SalaryOtherPayment::find($id);
        }elseif($module == 'loan_deductions'){
            $find_ = SalaryLoanDeduction::find($id);
        }else{
            $find_ = SalaryAllowance::find($id);
        }
        return $find_;
    }

    public function updateOrCreate($data, $allowance_id = null){
//        $data = array();
        if ($allowance_id){
            $updateCr = SalaryAllowance::where('allowance_id', $allowance_id)->update($data);
        }else{
            $updateCr = SalaryAllowance::create($data);
        }
        return $updateCr;
    }
    public function deleteAllowance($id){
        $delete =  SalaryAllowance::destroy($id);
        if ($delete == 1) {
            return true;
        }else{
            return false;
        }
    }
    // deductions
    public function find_deductions($id){
        return SalaryStatutoryDeduction::find($id);
    }
    public function get_list_deductions($paginateConfig, $user_id = null)
    {
        if (!$user_id){
            return array();
        }
        $query = SalaryAllowance::where('employee_id', $user_id)->orderBy('allowance_id','desc');
        $allowance = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $allowance;
    }
    public function updateOrCreateDeductions($data, $statutory_deductions_id = null){
        if ($statutory_deductions_id){
            $update_or_create = SalaryStatutoryDeduction::where('statutory_deductions_id', $statutory_deductions_id)->update($data);
        }else{
            $update_or_create = SalaryStatutoryDeduction::create($data);
        }
        return $update_or_create;
    }
    public function delete_deductions($id){
        $delete =  SalaryStatutoryDeduction::destroy($id);
        if ($delete == 1) {
            return true;
        }else{
            return false;
        }
    }
//    loan
    public function updateOrCreateLoanDeductions($data, $statutory_deductions_id = null){
        if ($statutory_deductions_id){
            $update_or_create = SalaryLoanDeduction::where('loan_deduction_id', $statutory_deductions_id)->update($data);
        }else{
            $update_or_create = SalaryLoanDeduction::create($data);
        }
        return $update_or_create;
    }
    public function delete_loan_deductions($id){
        $delete =  SalaryLoanDeduction::destroy($id);
        if ($delete == 1) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * kiểm tra user có phải leader không
     *
     * @param $id
     * @return mixed
     */
    public function checkLeader($id){
        return $this->model->where('reports_to', $id)->first();
    }

    /**
     * @param $company_id
     * @param null $name
     * @return mixed
     */
    public function getReportsTo($company_id, $name=null){
        $results = $this->model->where('company_id', $company_id);

        if($name != null){
            $results->where(function($q) use ($name){
                $q->whereRaw("concat(first_name, ' ', last_name) like '%$name%' ");
                $q->orWhereRaw("concat(last_name, ' ', first_name) like '%$name%' ");
            });
        }
        $results = $results->get();

        return $results;
    }

    /**
     * @param $company_id
     * @param null $name
     * @return mixed
     */
    public function getRoleByCompany($company_id, $name=null){
        $results = Role::where('company_id', $company_id);

        if($name != null){
            $results->where('name', 'LIKE', '%'.$name.'%');
        }
        $results = $results->get();

        return $results;
    }

    public function detailEmployee($id){
        $query = $this->model->with(['designation','department','office_shift','company','report_to'])
            ->where('user_id', $id)->first();
        return $query;
    }

    public function getEmployeeByIds($ids = [])
    {
        return $this->model->whereIn('user_id', $ids)->where('is_active', 1)->get();
    }

    public function search($conditions = []) {
        $without = $conditions['without'] ?? [];
        $withoutPaginate = $conditions['withoutPaginate'] ?? false;
        $ids = $conditions['ids'] ?? [];
        $query = $conditions['query'] ?? null;
        $employeeQuery = $this->model->query();
        if (count($without) > 0) {
            $employeeQuery->whereNotIn('user_id', $without);
        }
        $query && $query = addslashes($query) && $employeeQuery->where(function($subQuery) use($query) {
                $subQuery->whereRaw("CONCAT(first_name, last_name) LIKE '%$query%'")
                    ->whereOr('email', 'LIKE', "%$query%")
                    ->whereOr('employee_id', 'LIKE', "%$query%")
                    ->whereOr('contact_no', 'LIKE', "%$query%");
            });
        $employeeQuery->where('is_active', Employee::STATUS_ACTIVE);
        count($ids) > 0 && $employeeQuery->whereIn('user_id', $ids);
        return $withoutPaginate ? $employeeQuery->get() : $employeeQuery->paginate();
    }

    public function getUserIdEmployee($emloyee_id){
        $employee_ids = $this->model->selectRaw('user_id, last_name, first_name')->where('employee_id', $emloyee_id)->first();
        return $employee_ids;
    }

    public function getInfoByEmployeeId($employee_id){
        $employee_id = Employee::where('employee_id', $employee_id)->first();

        return $employee_id->toArray();
    }
}
