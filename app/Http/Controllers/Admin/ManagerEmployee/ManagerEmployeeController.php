<?php

namespace App\Http\Controllers\Admin\ManagerEmployee;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\MoneyMinus;
use App\Repositories\AssetRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DesignationRepository;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\LocationRepository;
use App\Repositories\MoneyMinusRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use App\Traits\DatatableResponseable;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
//use Maatwebsite\Excel\Excel;
use App\Imports\PlusMinus;
use App\Models\SalaryAllowance;
use Illuminate\Support\Facades\Redis;

use function GuzzleHttp\Promise\all;

class ManagerEmployeeController extends Controller
{
    private $employee;
    private $moneyMinus;
    private $asset;
    use DatatableResponseable;

    public function __construct(EmployeeRepository $employee, MoneyMinusRepository $moneyMinus, AssetRepository $asset)
    {
        $this->employee = $employee;
        $this->moneyMinus = $moneyMinus;
        $this->asset = $asset;
        if (\Route::is('employee_managements.staff_profile_set')) {
            View::share('adminViewProfileUser', false);
        } else {
            View::share('adminViewProfileUser', true);
        }

    }

    public function list()
    {
        $page_title = __('Quản lý nhân viên');
        return view('admin.employee.financial_overview', compact('page_title'));
    }
    public function staff_profile_set()
    {
        if(Auth::user()->isAdmin()){
            $allDeparment = Department::where('company_id', Auth::user()->company_id)->get();
            $allUser = $this->employee->getUserByCompnayId(Auth::user()->company_id);
            $listRole = $this->employee->getListRole(Auth::user()->company_id);
            $page_title = __('Thiết lập hồ sơ nhân viên');
            return view('admin.employee.staff_profile_set', compact('page_title','allDeparment','allUser','listRole'));
        } else {
            return redirect('/');
        }
    }
    public function setStaff($id)
    {
        $page_title = __('Thiết lập hồ sơ nhân viên');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        //$date_formart = Carbon::createFromFormat('Y-m-d' ,$getUser->end_trail_work);
        //$date_fm = $date_formart->format('m-d-Y');
        /*$date_fm = (isset($getUser->end_trail_work))?Carbon::createFromFormat('m-d-Y' ,$getUser->end_trail_work):date('d-m-Y');
        $gender = $getUser->gender;
        $marital_status = $getUser->marital_status;*/
        return view('admin.employee.setStaff', compact('page_title','getUser'/*,'gender','marital_status','date_fm'*/));
    }
    public function staff_allowance($id)
    {
        $page_title = __('money_plus');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        $date_formart = $getUser->end_trail_work ? Carbon::createFromFormat('Y-m-d' ,$getUser->end_trail_work) : null;
        $date_fm = $date_formart ? $date_formart->format('m-d-Y') : null;
        $gender = $getUser->gender;
        $marital_status = $getUser->marital_status;
        return view('admin.employee.allowance', compact('page_title','getUser','gender','marital_status','date_fm'));
    }
    public function statutory_deductions($id)
    {
        $page_title = __('xin_employee_set_statutory_deductions');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        $getDeductions = $this->employee->find_deductions($id);
        $gender = $getUser->gender;
        $marital_status = $getUser->marital_status;
        return view('admin.employee.statutory_deductions', compact('page_title','getUser','gender','marital_status','getDeductions'));
    }
    public function other_payment($id)
    {
        $page_title = __('xin_payroll_allowances');
        $getUser = $this->employee->find($id);
//        dd($getUser->end_trail_work);
        $date_formart = $getUser->end_trail_work ? Carbon::createFromFormat('Y-m-d' ,$getUser->end_trail_work) : null;
        $date_fm = $date_formart ? $date_formart->format('m-d-Y') : null;
        $gender = $getUser->gender;
        $marital_status = $getUser->marital_status;
        return view('admin.employee.other_payment', compact('page_title','getUser','gender','marital_status','date_fm'));
    }
    public function loan_deductions($id)
    {
        $page_title = __('xin_employee_set_loan_deductions');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        $date_formart = $getUser->end_trail_work ? Carbon::createFromFormat('Y-m-d' ,$getUser->end_trail_work) : null;
        $date_fm = $date_formart ? $date_formart->format('m-d-Y') : null;
        $gender = $getUser->gender;
        $marital_status = $getUser->marital_status;
        return view('admin.employee.loan_deductions', compact('page_title','getUser','gender','marital_status','date_fm'));
    }
    public function fine($id)
    {
        $page_title = __('xin_employee_all_month_mulct_list');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view('admin.employee.fine', compact('page_title','getUser'));
    }

    public function money_minus($id)
    {
        $page_title = __('money_minus');
        $getUser = $this->employee->find($id);
        if ($getUser == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view('admin.employee.money_minus', compact('page_title','getUser'));
    }

    public function datatableMoneyMinus(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $listUserMoneyMinus = $this->moneyMinus->getUserMoneyMinus($paginateConfig, $request->id);

        return $this->makeDatatableResponse($listUserMoneyMinus, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormMoneyMinus(Request $request) {
        $document_type = MoneyMinus::all();
        $id = $request->get('id', null);
        $moneyMinus = null;

        if($id){
            $type = 'updated';
            $moneyMinus = $this->moneyMinus->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.employee.form_money_minus', compact('moneyMinus', 'type', 'document_type'));
    }

    public function createMoneyMinusUser(Request $request) {

        $year_month = $request->year_month ? Carbon::createFromFormat('m-Y', $request->year_month) : '';
        $year_month = $year_month != '' ? $year_month->format('Y-m') : '';
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'money' => 'required',
            ],
            [
                'title.required' => __('Trường tiêu đề không được bỏ trống.'),
                'money.required' => __('Trường số tiền không được bỏ trống.'),
            ]);

        if ($validator->passes()) {

            $data = [
                "user_id" => $request->user_id,
                "title" => $request->title,
                "amount_option" => $request->amount_option ? $request->amount_option : 1,
                "money" => str_replace('.', '', $request->money),
                "year_month" => $year_month
            ];

            if ($id = $request->get('id')) {
                $response = $this->moneyMinus->update($id, $data);
            } else {
                $response = $this->moneyMinus->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function deleteMoneyMinusUser(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->moneyMinus->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    //----------Request Ajax---------
    //request - list_staff
    public function list_staff(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $allowance = $this->employee->listStaffbyCompany($paginateConfig, $request);
        return $this->makeDatatableResponse($allowance, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    public function listEmployeeDepartment(Request $request){
        $get_Deparment = $this->employee->getUserByDeparment($request->department_id);
        return response()->json($get_Deparment);
    }
    //
    public function staff_update(Request $request){
            $dates = Carbon::createFromFormat('d-m-Y', $request->end_trail_work);
            $date = $dates->format('Y-m-d');

        if($request->ways_type == 2){
            $data = array(
                'wages_type' => $request->ways_type,
                'salary_trail_work' => str_replace('.', '',$request->salary_trail_work),
                'basic_salary' => str_replace('.', '',$request->basic_salary),
                'end_trail_work' => $date
            );
        } else {
            $data = array(
                'wages_type' => $request->ways_type,
                'basic_salary' => str_replace('.', '',$request->basic_salary)
            );
        }
        $update = $this->employee->update($request->employee_id,$data);
        if ($update){
            return $this->responseSuccess(__('xin_theme_success'));
        }else{
            return $this->responseError(__("update_error"));
        }
    }
    public function list_allowance(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $allowance = $this->employee->listDatatable($paginateConfig, $request->user_id);
        return $this->makeDatatableResponse($allowance, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    public function updateRequest(Request $request){

        $year_month = $request->year_month ? Carbon::createFromFormat('m-Y', $request->year_month)->format('Y-m') : '';
        $data = array(
              "allowance_title" => $request->allowance_title,
              "allowance_amount" => str_replace(".", "", $request->allowance_amount),
              "is_allowance_taxable" => $request->is_allowance_taxable,
              "amount_option" => $request->amount_option,
              "employee_id" => $request->employee_id,
               "year_month" => $year_month
        );
        $updateOrCreate = $this->employee->updateOrCreate($data, $request->allowance_id);
        if ($updateOrCreate) {
            return $this->responseSuccess(__('xin_theme_success'));
        } else{
            return $this->responseError(__('update_error'));
        }
    }
    public function deleteAllowance(Request $request){
        $delete_recor = $this->employee->deleteAllowance($request->id);
        return $delete_recor;
    }
    // request deductions
    public function list_deductions(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $type = 'deductions';
        $allowance = $this->employee->listDatatable($paginateConfig, $request->user_id, $type);
        return $this->makeDatatableResponse($allowance, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    //load form
    public function createForm(Request $request) {

        $id = $request->get('id', null);
        $employee_id = $request->get('employee_id', null);
        $module = $request->module;
//        if ($module == 'deductions'){
//
//        }
//        $find_data_module = $this->employee->findDataModule($id,$module);
//        else{
//            $find_data_module = $this->employee->findDataModule($id,$module);
//            if($id){
//                $type = 'updated';
//                $find_data = $this->employee->findAllowance($id);
//                $find_data_module = $this->employee->findDataModule($id,$module);
//            } else {
//                $type = 'created';
//            }
//        }
        $allowance = null;
        if($id){
            $type = 'updated';
            $allowance = $this->employee->findAllowance($id,$module);
        } else {
            $type = 'created';
        }
        return view('admin.employee.form_modal', compact('allowance', 'type','employee_id','module'));
    }
    // load form deductions - hand deduction
    public function createFormDeductions(Request $request) {
        $id = $request->get('id', null);
        $employee_id = $request->get('employee_id', null);
        $module = $request->module;
        $find_data_module = null;
        if($id){
            $type = 'updated';
            $find_data_module = $this->employee->findDataModule($id,$module);
        } else {
            $type = 'created';
        }
        return view('admin.employee.modal_form.deductions_form', compact('find_data_module', 'type','employee_id','module'));
    }
    public function updateRequestDeductions(Request $request){
        $data = array(
            "deduction_title" => $request->deduction_title,
            "basic_salary_region" => $request->basic_salary_region,
            "deduction_amount" => $request->basic_salary_region * $request->tax_percent/100,
            "tax_percent" => $request->tax_percent,
            "statutory_options" => $request->statutory_options,
            "employee_id" => $request->employee_id
        );
        $updateOrCreate = $this->employee->updateOrCreateDeductions($data, $request->statutory_deductions_id);
        if ($updateOrCreate) {
            return $this->responseSuccess(__('xin_theme_success'));
        } else{
            return $this->responseError(__('update_error'));
        }
    }
    public function deleteRequestDeductions(Request $request){
        $delete_recor = $this->employee->delete_deductions($request->id);
        return $delete_recor;
    }
    // other_payment
    public function list_otherPayment(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $type = 'otherPayment';
        $otherPayment = $this->employee->listDatatable($paginateConfig, $request->user_id, $type);
        return $this->makeDatatableResponse($otherPayment, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    public function createFormOtherPayment(Request $request) {
        $id = $request->get('id', null);
        $employee_id = $request->get('employee_id', null);
        $module = $request->module;
        $find_data_module = null;
        if($id){
            $type = 'updated';
            $find_data_module = $this->employee->findDataModule($id,$module);
        } else {
            $type = 'created';
        }
        return view('admin.employee.modal_form.deductions_form', compact('find_data_module', 'type','employee_id','module'));
    }
    // list_loanDeductions
    public function list_loanDeductions(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $type = 'loan_deductions';
        $otherPayment = $this->employee->listDatatable($paginateConfig, $request->user_id, $type);
        return $this->makeDatatableResponse($otherPayment, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    public function createFormloanDeductions(Request $request) {
        $id = $request->get('id', null);
        $employee_id = $request->get('employee_id', null);
        $module = $request->module;
        $find_data_module = null;
        if($id){
            $type = 'updated';
            $find_data_module = $this->employee->findDataModule($id,$module);
        } else {
            $type = 'created';
        }
        return view('admin.employee.modal_form.loan_deductions_form', compact('find_data_module', 'type','employee_id','module'));
    }
    public function updateRequestloanDeductions(Request $request){

        $data = array(
            "loan_deduction_title" => $request->loan_deduction_title,
            "loan_options" => $request->loan_options,
            "loan_deduction_amount" =>$request->loan_deduction_amount,
            "monthly_installment" => $request->monthly_installment,
            "is_deduction_salary" => $request->is_deduction_salary ? $request->is_deduction_salary : 0 ,
            "employee_id" => $request->employee_id,
            "reason" => $request->reason,
            "start_date" => 0,
            "end_date" => 0,
            "loan_time" => 0,
            "total_paid" => 0,
            "status" => 0,
            "is_deducted_from_salary" => 0,
            "loan_deduction_amount" => 0,
        );
        $updateOrCreate = $this->employee->updateOrCreateLoanDeductions($data, $request->loan_deduction_id);
        if ($updateOrCreate) {
            return $this->responseSuccess(__('xin_theme_success'));
        } else{
            return $this->responseError(__('update_error'));
        }
    }
    public function deleteRequestloanDeductions(Request $request){
        $delete_recor = $this->employee->delete_loan_deductions($request->id);
        return $delete_recor;
    }

    /**'
     * tạo form tạo và cập nhật user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createFormEmployee(Request $request){
        $id = $request->get('id', null);
        $companies = (new CompanyRepository())->getListCompany();
        $employeeDetail = null;
        $listLeaveType = null;
        if($id){
            $type = 'updated';
            $employeeDetail = $this->employee->detailEmployee($id);
            $listLeaveType = (new LeaveTypeRepository())->getLeaveType();
        } else {
            $type = 'created';
        }
        return view('admin.employee.create_form', compact('companies','employeeDetail','type','id','listLeaveType'));
    }

    /**
     * list role by company
     *
     * @param Request $request
     * @return mixed
     */
    public function roleByCompany(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $roles = $this->employee->getRoleByCompany($company_id);
            } else {
                $roles = $this->employee->getRoleByCompany($company_id, $term);
            }
            foreach ($roles as $role) {
                $formatted_tags[] = ['id' => $role->id, 'text' => $role->name];
            }
        }

        return \Response::json($formatted_tags);
    }

    /**
     * list designation by company and department
     *
     * @param Request $request
     * @return mixed
     */
    public function getDesignation(Request $request){
        $company_id = trim($request->company_id);
        $department_id = trim($request->department_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id && $department_id){
            if (empty($term)) {
                $designations = (new DesignationRepository())->getDesignation($company_id, $department_id);
            } else {
                $designations = (new DesignationRepository())->getDesignation($company_id, $department_id, $term);
            }
            foreach ($designations as $designation) {
                $formatted_tags[] = ['id' => $designation->designation_id, 'text' => $designation->designation_name];
            }
        }

        return \Response::json($formatted_tags);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getLeaveType(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $types = (new LeaveTypeRepository())->getLeaveTypeByCompany($company_id);
            } else {
                $types = (new LeaveTypeRepository())->getLeaveTypeByCompany($company_id, $term);
            }
            foreach ($types as $type) {
                $formatted_tags[] = ['id' => $type->leave_type_id, 'text' => $type->type_name];
            }
        }
        return \Response::json($formatted_tags);
    }

    public function getLocation(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $types = (new LocationRepository())->getLocationByCompany($company_id);
            } else {
                $types = (new LocationRepository())->getLocationByCompany($company_id, $term);
            }
            foreach ($types as $type) {
                $formatted_tags[] = ['id' => $type->location_id, 'text' => $type->location_name];
            }
        }
        return \Response::json($formatted_tags);
    }
    private function addNumberEmployeeId($employee_id)
    {
        $total_string_len_in_system = 5;
        $count_string = strlen($employee_id);
        $new_employee_id = "";
        for ($i = 0; $i < ($total_string_len_in_system - $count_string); $i++) {
            $new_employee_id .= "0";
        }
        return $new_employee_id . $employee_id;
    }
    public function storeRequest(EmployeeRequest $request)
    {
        $redis = Redis::connection();
        $employee_id=$this->addNumberEmployeeId($request->employee_id);

        try {
            DB::beginTransaction();
            $data = [
                'employee_id' => $employee_id,
                'office_shift_id' => $request->office_shift_id,
                'reports_to' => $request->reports_to,
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'username' => trim($request->username),
                'company_id' => $request->company_id,
                'location_id' => $request->location_id,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'pincode' => $request->pin_code,//$this->input->post('pin_code'),
                'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
                'gender' => $request->gender,
                'user_role_id' => $request->role,
                'department_id' => $request->department_id,
                /*'sub_department_id' => $request->subdepartment_id,*/
                'designation_id' => $request->designation_id,
                'date_of_joining' => date('Y-m-d', strtotime($request->date_of_joining)),
                'contact_no' => $request->contact_no,
                'address' => $request->address,
                'is_active' => 1,
                'leave_categories' => implode(',', $request->leave_categories),
                'salary_trail_work' => $request->salary_trail_work,
                'end_trail_work' => date('Y-m-d', strtotime($request->end_trail_work)),
                'basic_salary' => $request->basic_salary,
                'wages_type' => 2
            ];


            if ($id = $request->get('user_id')) {
                unset($data['password']);
                unset($data['pincode']);
                unset($data['wages_type']);
                $data['is_active'] = $request->status;
                $data['date_of_leaving'] = $request->date_of_leaving;
                $data_user = array(
                    'uid' => $id,
                    "first_name" => $data['first_name'],
                    "last_name" => $data['last_name'],
                    "username" => $data['username'],
                    "email" => $data['email'],
                    "phone" => $data['contact_no'],
                    'activated' => $request->status

                );
                if (app('hrm')->isSSO()) {
                    try {
                        $ch = curl_init();
                        $url = config('services.sso.url') . '/api/edit';
                        $header = array(
                            'Content-Type:application/json'
                        );
                        $query = http_build_query(array('my_token' => config('services.sso.broker_secret')));
                        $payload = json_encode($data_user);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        $result = curl_exec($ch);
                        Log::info($result);
                        curl_close($ch);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error($e->getMessage());
                        return $this->responseError(__('xin_error_msg'));
                    }
                }
                $iresult = $this->employee->update($id,$data);
                if($iresult){
                    DB::commit();
                    return $this->responseSuccess(__('xin_success_update_employee'));
                } else{
                    DB::rollBack();
                    return $this->responseError(__('xin_error_msg'));
                }
            } else {
                $iresult = $this->employee->create($data);

                if ($iresult) {
                    if (app('hrm')->isSSO()) {
                        try {
                            $data_user = array(
                                "id" => $iresult->user_id,
                                "first_name" => $data['first_name'],
                                "last_name" => $data['last_name'],
                                "username" => $data['username'],
                                "email" => $data['email'],
                                "password" => $request->password,
                                "terms" => true
                            );
                            $ch = curl_init();
                            $url = config('services.sso.url') . '/api/register';
                            $header = array(
                                'Content-Type:application/json'
                            );
                            $query = http_build_query(array('my_token' => config('services.sso.broker_secret')));
                            $payload = json_encode($data_user);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                            curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                            $result = curl_exec($ch);
                            Log::info($result);
                            curl_close($ch);
                            DB::commit();
                            return $this->responseSuccess(__('xin_success_add_employee'));
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error($e->getMessage());
                            return $this->responseError(__('xin_error_msg'));
                        }
                    }
                    // success
                    DB::commit();
                    return $this->responseSuccess(__('xin_success_add_employee'));
                    $employee_id = $redis->lpush('employee_id',$data['employee_id']);

                    $first_name = $redis->lpush('first_name',$data['first_name']);
                    $last_name = $redis->lpush('last_name',$data['last_name']);
                } else {
                    DB::rollBack();
                    return $this->responseError(__('xin_error_msg'));
                }
            }

            /*$redis = \Redis::connection();
            $key_redis = array(
                'action'=> 'add_user',
                'data' => $data
            );
            $redis->rPush("ATTENDANCE_User_Manager", json_encode($key_redis));*/
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function apiUpdateTK($data_user){
        $ch = curl_init();
        $url = config('services.sso.url').'/api/register';
        $header = array(
            'Content-Type:application/json'
        );
        $query = http_build_query(array('my_token' => config('services.sso.broker_secret')));
        $payload = json_encode( $data_user );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch,CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function changePasswordFormEmployee(Request $request)
    {
        $id = $request->get('id', null);
        $profile = null;
        if ($id) {
            $profile = $this->employee->find($id);
        }
        return view('admin.employee.form_change_password', compact('profile'));
    }

    public function changePasswordEmployee(Request $request)
    {
        $rules = array(
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        );
        $messages = array(
            'required' => __('field_required'),
            'confirm_password.same' => __('xin_employee_confirm_password_err'),
        );
        $request->validate($rules, $messages);

        $response = $this->employee->update($request->id, ['password' => bcrypt($request->password)]);

        if($response && app('hrm')->isSSO()) {
            $data = [];
            $data['user_id'] = $request->id;
            $data['password'] = $request->password;
            $ch = curl_init();
            $url = config('services.sso.url').'/api/passupdate';
            $header = array(
                'Content-Type:application/json'
            );
            $query = http_build_query(array('my_token' => config('services.sso.broker_secret')));
            $payload = json_encode( $data );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt($ch,CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        if ($response) {
            return $this->responseSuccess(__('update_success'));
        } else{
            return $this->responseError(__('update_error'));
        }
    }
    /**
     * change is_active employee
     */
    public function changeIsActive(Request $request){
        $is_active = $request->is_active;
        $employee_id = $request->employee_id;
        // dd($employee_id);
        $response = $this->employee->update($employee_id, ['is_active' => $is_active]);
        if($response && app('hrm')->isSSO()) {
            $data = [];
            $data['user_id'] = $request->employee_id;
            $data['activated'] = $is_active;
            $ch = curl_init();
            $url = config('services.sso.url').'/api/statusupdate';
            $header = array(
                'Content-Type:application/json'
            );
            $query = http_build_query(array('my_token' => config('services.sso.broker_secret')));
            $payload = json_encode( $data );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt($ch,CURLOPT_URL, $url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        if ($response) {
            return $this->responseSuccess(__('update_success'));
        } else{
            return $this->responseError(__('update_error'));
        }
    }
        /**
     * change update finger
     */
    public function changeIsFinger(Request $request){
        $is_finger = $request->is_finger;

        $user_id = $request->user_id;
        if($is_finger ==0){
            $response = $this->employee->update($user_id, ['status_finger' => $is_finger,'status_new_user'=>1]);
        }else{
        $response = $this->employee->update($user_id, ['status_finger' => $is_finger]);
        }
        if ($response) {
            return $this->responseSuccess(__('xin_employee_update_finger'));
        }else{
            return $this->responseError(__('update_error'));
        }

    }
    // MANG XA HOI
    public function social()
    {
        $page_title = __('xin_e_details_social');

        //info user
        $info_user = Employee::where('user_id', request()->route('id'))->first();
        if ($info_user == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view("admin.employee.account_social", compact('page_title', 'info_user'));
    }

    public function updateSocial(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'facebook_link' => 'nullable|url',
                'twitter_link' => 'nullable|url',
                'blogger_link' => 'nullable|url',
                'youtube_link' => 'nullable|url',
            ],
            [
//                'facebook_link.url' => 'Trường tên tài khoản không được để trống !',
//                'twitter_link.url' => 'Trường tên ngân hàng không được để trống !',
//                'blogger_link.url' => 'Trường số tài khoản không được để trống !',
//                'youtube_link.url' => 'Trường chi nhánh không được để trống !',
            ]);

        if ($validator->passes()) {

            $user_id = $request->route('id');

            $data = [
                'facebook_link' => $request->facebook_link,
                'twitter_link' => $request->twitter_link,
                'blogger_link' => $request->blogger_link,
                'youtube_link' => $request->youtube_link
            ];

            $response = $this->employee->update($user_id, $data);
            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }

        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }

    // Tai san duoc giao
    public function assetAssign()
    {
        $page_title = __('xin_asset_assign');
        //info user
        $info_user = Employee::where('user_id', request()->route('id'))->first();
        if ($info_user == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view("admin.employee.account_asset_assign", compact('page_title', 'info_user'));
    }

    public function listAsset(Request $request)
    {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $info_user = Employee::where('user_id', request()->route('id'))->first();
        $getAsset = $this->asset->getAseetUser($paginateConfig, $info_user);

        return $this->makeDatatableResponse($getAsset, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * read file Excel
     */
    public function readFileExcel(Request $request){
        $file = $request->file('excel_upload');
        $read_data = \Maatwebsite\Excel\Facades\Excel::toArray(new PlusMinus(), $file);
        $data_employee = array();
        array_shift($read_data[0]);
        foreach ($read_data[0] as $key => $data){
            $employee_id = $this->employee->getUserIdEmployee($data[2]);
            $data_employee[] = array(
                "name" => $data[1],
                "employee_id" => $data[2],
                "plus" => array(
                    "allowance_title" => $data[3] == null ? '' : $data[3],
                    "allowance_amount" => $data[4] == null ? '' : intval($data[4]),
                    "is_allowance_taxable" =>  $data[4] == null ? '' :0,
                    "amount_option" => $data[4] == null ? '' : 1,
                    "employee_id" =>  $data[2] == null ? '' : $employee_id->user_id,
                    "year_month" => $data[5] == null ? '' : Carbon::createFromFormat('m-Y', $data[5])->format('Y-m')
                ),
                "minus" => array(
                    "user_id" => $data[2] == null ? '' : $employee_id->user_id,
                    "title" => $data[6] == null ? '' : $data[6],
                    "amount_option" => $data[9] == null ? '' : $data[9],
                    "money" => $data[7] == null ? '' : intval($data[7]),
                    "year_month" => $data[8] == null ? '' : Carbon::createFromFormat('m-Y', $data[8])->format('Y-m')
                )
            );
        }
        return $data_employee;
    }
    /**
     * @param object
     * update list excel upload (plus & minus)
     * @return bool
     */
    public function updateFileExcel(Request $request){
        $data = json_decode($request->obj_excel);

        try {
            DB::beginTransaction();
            foreach ($data as $item){
                    $data_plus = array(
                        "allowance_title" => $item->plus->allowance_title,
                        "allowance_amount" => $item->plus->allowance_amount,
                        "is_allowance_taxable" => $item->plus->is_allowance_taxable,
                        "amount_option" => $item->plus->amount_option,
                        "employee_id" => $item->plus->employee_id,
                        "year_month" => $item->plus->year_month
                    );
                    $data_minus = [
                        "user_id" => $item->minus->user_id,
                        "title" => $item->minus->title,
                        "amount_option" => $item->minus->amount_option,
                        "money" => $item->minus->money,
                        "year_month" => $item->minus->year_month
                    ];
                if ($item->minus->title && $item->minus->money){
                    $this->moneyMinus->create($data_minus);
                }
                if ($item->plus->allowance_amount && $item->plus->allowance_title) {
                    SalaryAllowance::create($data_plus);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    /**
     * create data minus from detail salary (for admin)
     */
    public function post_data_minus(Request $request){
        return $this->moneyMinus->create_data_minus($request);
    }
}
