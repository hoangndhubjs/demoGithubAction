<?php

namespace App\Http\Controllers\Leaves;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Notifications\Slack;
use App\Notifications\SlackNotification;
use App\Repositories\CompanyRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeesPheptonRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OfficeShiftRepository;
use App\Traits\DatatableResponseable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeavesController extends Controller
{
    use DatatableResponseable;

    private $leave;
    private $leave_type;

    public function __construct(LeaveRepository $leave, LeaveTypeRepository $leave_type)
    {
        $this->leave = $leave;
        $this->leave_type = $leave_type;
    }

    public function index() {
        $page_title = 'Quản lý nghỉ phép';
        $page_description = '';

        // get leave_type
        $leaveTypes = $this->leave_type->getLeaveType();

        return view('pages.leaves.list', compact('page_title', 'page_description', 'leaveTypes'));
    }

    public function listLeaves(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        /* if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('create_date');
        }*/
        $orders = $this->leave->getLeavesByUserId($paginateConfig);
        //$orders = $this->mealOrder->getMyOrders($paginateConfig, $request->get('start_date'), $request->get('end_date'));
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function store(LeaveRequest $request){
        $inputs = $request->all();
        $leave_half_day_opt = 0;
        $no_of_days = 0;
        $is_salary= 0;
        $fname = '';
        $start_date = $inputs['start_date'];
        $end_date = $inputs['end_date'];
        $start_time = $inputs['xin_start_time']??'';
        $end_time = $inputs['xin_end_time']??'';

        if($inputs['leave_time_types'] == Leave::LEAVE_ALL_DAY){
            $datetime1 = new \DateTime($start_date);
            $datetime2 = new \DateTime($end_date);
            $interval = $datetime1->diff($datetime2);
            $no_of_days = $interval->format('%a') + 1;
            if($inputs['leave_type'] == Leave::LEAVE_TYPE_PHEP_TON || $inputs['leave_type'] == Leave::LEAVE_TYPE_CONG_TAC){
                $is_salary= 1;
            }
        } elseif($inputs['leave_time_types'] == Leave::LEAVE_HALF_DAY){
            $start_date = $inputs['start_date_half_day'];
            $end_date = $inputs['start_date_half_day'];
            $type_half_day = $inputs['type_half_day'];
            $leave_half_day_opt = 1;
            $no_of_days = 0.5;
            if($inputs['leave_type'] == Leave::LEAVE_TYPE_PHEP_TON || $inputs['leave_type'] == Leave::LEAVE_TYPE_CONG_TAC){
                $is_salary= 1;
            }
        } elseif($inputs['leave_time_types'] == Leave::LEAVE_IN_DAY){
            $start_date = $inputs['start_date_in_day'];
            $end_date = $inputs['start_date_in_day'];
            /*if(strtotime($start_date) >= strtotime($end_date)){
                return $this->responseError( __('xin_error_start_end_time'));
            }*/
            // get leave user by month by employee_id
            $leave_in_month = $this->leave->getLeavesInMonthByEmployeeId(date('Y-m-d', strtotime($start_date)), Auth::user()->user_id);

            if(Auth::user()->office_shift_id == 0 || Auth::user()->office_shift_id == ''){
                return $this->responseError( __('xin_office_shift_not_assigned'));
            } else {
                $office_shift = (new OfficeShiftRepository())->find(Auth::user()->office_shift_id);
                $shift = strtolower(date('l', strtotime($start_date)));
                $out = $shift.'_out_time';
                $end_time = $office_shift->$out;
                $start_time = date('H:i',strtotime('-1 hour',strtotime($office_shift->$out)));
                // get total time leave
                $no_of_days = $this->leave->noOfDays($start_date, $start_time, $end_time);
            }
            if($no_of_days > 0){
                $is_salary = 1;
            } else {
                return $this->responseError( __('xin_error_time_leave_max_1h'));
            }
        }

        if(!$this->leave->checkExistLeave(Auth::user()->user_id, date('Y-m-d',strtotime($start_date)), date('Y-m-d',strtotime($end_date)), $inputs['leave_time_types'], isset($type_half_day)?$type_half_day:null)){
            return $this->responseError( __('xin_error_leave_exist'));
        }

        if($inputs['leave_time_types'] != Leave::LEAVE_IN_DAY) {
            // leave remaining total
            /*$leave_remaining_total = $this->leave->getLeaveRemainingTotal($inputs['leave_type'], Auth::user()->user_id);

            if ($leave_remaining_total < $no_of_days) {
                return $this->responseError( __('xin_hrsale_leave_quota_completed'));
            }*/

            // check phep tồn
            if(isset($inputs['leave_type']) && $inputs['leave_type'] == Leave::LEAVE_TYPE_PHEP_TON){
                $phepton = (new EmployeesPheptonRepository())->checkPhepTon([
                    'employee_id' => Auth::user()->user_id,
                    'leave_type_id'	=> Leave::LEAVE_TYPE_PHEP_TON,
                    'year' => date('Y')
                ]);
                if(!isset($phepton) || $phepton->remain_of_number < $no_of_days){
                    return $this->responseError( __('xin_hrsale_leave_quota_completed'));
                }
            }
        }

        // kiểm tra giới hạn ngày nghỉ theo leave type
        $leave_type = (new LeaveTypeRepository())->find($inputs['leave_type']);

        // nếu có giới hạn theo tuần (số lần/tuần) thì
        // nếu có giới hạn theo tháng (số lần/tháng) thì
        // nếu có giới hạn theo năm thì
        if(isset($leave_type) && isset($leave_type->days_per_week) && $leave_type->days_per_week > 0){
            // đếm số lần nghỉ trong tuần
            $date = Carbon::createFromFormat("d-m-Y", $start_date);
            $first_day = $date->startOfWeek()->format('Y-m-d');
            $last_day = $date->endOfWeek()->format('Y-m-d');
            $total = $this->leave->count_day_leaves_by_date($inputs['leave_type'],Auth::user()->user_id ,$first_day, $last_day);
            if($total >= $leave_type->days_per_week){
                return $this->responseError( __('xin_error_max_week'));
            }
        }
        if(isset($leave_type) && isset($leave_type->days_per_month) && $leave_type->days_per_month > 0){
            // đếm số lần nghỉ trong tháng
            $first_day = date('Y-m-01', strtotime($start_date));
            $last_day = date('Y-m-t', strtotime($start_date));
            $total = $this->leave->count_day_leaves_by_date($inputs['leave_type'],Auth::user()->user_id ,$first_day, $last_day);
            if($total >= $leave_type->days_per_month){
                return $this->responseError( __('xin_error_max_month'));
            }
        }

        $data = [
            'company_id' => $inputs['company_id'],
            'employee_id' => $inputs['employee_id'],
            'department_id' => Auth::user()->department_id,
            'leave_type_id' => $inputs['leave_type'],
            'from_date' => date('Y-m-d',strtotime($start_date)),
            'to_date' => date('Y-m-d',strtotime($end_date)),
            'from_time' => ($start_time)?date('H:i',strtotime($start_time)):'',
            'to_time' => ($end_time)?date('H:i',strtotime($end_time)):'',
            'applied_on' => date('Y-m-d H:i:s'),
            'reason' => $inputs['reason'],
            'remarks' => ($inputs['remarks'])?$inputs['remarks']:'',
            'status' => 1,
            'is_half_day' => $leave_half_day_opt,
            'is_notify' => 1,
            /*'leave_attachment' => $fname,*/
            /*'confirm' => 0,*/
            'total_day_leave' => $no_of_days,
            'leave_time_types' => $inputs['leave_time_types'],
            'type_half_day' => isset($type_half_day)?$type_half_day:'',
            'is_salary' => $is_salary,
        ];

        if($request->file()) {
            $fileName = time().'_'.$request->file('attachment')->getClientOriginalName();
            $filePath = $request->file('attachment')->storeAs('uploads', $fileName, 'public');
            if($filePath){
                $data['leave_attachment'] = time().'_'.$request->file('attachment')->getClientOriginalName();
            }
        }

        $this->leave->create($data);
        return $this->responseSuccess(__('Thành công'));
    }

    public function update(LeaveRequest $request, $id)
    {
        $data = $request->all();
        $this->leave->update($id, $data);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function detail($id){
        //if($this->leave->find($id)){
            $edata = array(
                'is_notify' => 0,
            );
            $type = (new NotificationRepository())->updateNotificationRecord($edata,$id, Auth::user()->user_id,'leave');
        //}
        return redirect(route('leaves.list'));
    }

    /**
     * view admin
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminList(){
        $page_title = 'Quản lý nghỉ phép';
        $page_description = '';

        // get leave_type
        $leaveTypes = $this->leave_type->getLeaveType();

        // company
        $companies = (new CompanyRepository())->getListCompany();

        $status = [
            1 => 'Leader chưa duyệt',
            2 => 'Từ chối',
            3 => 'Chưa xác nhận',
            4 => 'Xác nhận',
        ];
        // check role admin and leader
        if(Auth::user()->isAdmin()){
            return view('pages.leaves.admin-list', compact('page_title', 'page_description', 'leaveTypes', 'companies', 'status'));
        }
        if((new EmployeeRepository())->checkLeader(Auth::user()->user_id)){
            return view('pages.leaves.leader-list', compact('page_title', 'page_description', 'leaveTypes', 'companies', 'status'));
        }

        return redirect('/');
    }

    /**
     * List leave view admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminAjaxList(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        /* if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('create_date');
        }*/
        $orders = $this->leave->getLeavesByAdmin($paginateConfig, $request);
        //$orders = $this->mealOrder->getMyOrders($paginateConfig, $request->get('start_date'), $request->get('end_date'));
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminConfirm(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_update"));
        }
        // kiểm tra số phép tồn
        // check số phép còn
        if($request->get('confirm') == Leave::CONFIRM_APPROVE){
            $checkLeave = $this->leave->find($id);
            if(!$this->leave->checkExistLeave($checkLeave->employee_id, $checkLeave->from_date, $checkLeave->to_date, $checkLeave->leave_time_types, $checkLeave->type_half_day, $checkLeave->leave_id)){
                return $this->responseError( __('xin_error_leave_exist'));
            }
            if($checkLeave->leave_type_id == Leave::LEAVE_TYPE_PHEP_TON){
                $phepton = (new EmployeesPheptonRepository())->checkPhepTon([
                    'employee_id' => $checkLeave->employee_id,
                    'leave_type_id'	=> Leave::LEAVE_TYPE_PHEP_TON,
                    'year' => date('Y')
                ]);
                if(!isset($phepton) || (isset($phepton) && $phepton->remain_of_number < $checkLeave->total_day_leave)){
                    return $this->responseError( __('xin_hrsale_leave_quota_completed'), 400);
                } else {
                    $newPhepTon = [
                        'employee_id' => $checkLeave->employee_id,
                        'leave_type_id'	=> Leave::LEAVE_TYPE_PHEP_TON,
                        'year' => date('Y'),
                        'used_of_number' => $phepton->used_of_number + $checkLeave->total_day_leave,
                        'remain_of_number' => $phepton->remain_of_number - $checkLeave->total_day_leave
                    ];
                }
            }
        }
        if ($this->leave->update($id, ['confirm'=> $request->get('confirm')])) {
            //trừ phép năm còn
            if(isset($newPhepTon) && isset($phepton)){
                $phepton->update($newPhepTon);
            }
            return $this->responseSuccess(__("update_success"));
        }
        return $this->responseError(__("update_fail"));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function listDepartment(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $departments = (new DepartmentRepository())->getDepartmentByCompany($company_id);
            } else {
                $departments = (new DepartmentRepository())->getDepartmentByCompany($company_id, $term);
            }
            foreach ($departments as $department) {
                $formatted_tags[] = ['id' => $department->department_id, 'text' => $department->department_name];
            }
        }

        return \Response::json($formatted_tags);
    }

    public function leaderAjaxList(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $orders = $this->leave->getLeavesByLeader($paginateConfig, $request);
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function leaderApprove(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_update"), 400);
        }
        // check số phép còn
        if($request->get('approve') == Leave::STATUS_CONFIRMED){
            $checkLeave = $this->leave->find($id);
            if(!$this->leave->checkExistLeave($checkLeave->employee_id, $checkLeave->from_date, $checkLeave->to_date, $checkLeave->leave_time_types, $checkLeave->type_half_day, $checkLeave->leave_id)){
                return $this->responseError( __('xin_error_leave_exist'));
            }
            if($checkLeave->leave_type_id == Leave::LEAVE_TYPE_PHEP_TON){
                $phepton = (new EmployeesPheptonRepository())->checkPhepTon([
                    'employee_id' => $checkLeave->employee_id,
                    'leave_type_id'	=> Leave::LEAVE_TYPE_PHEP_TON,
                    'year' => date('Y')
                ]);
                if(!isset($phepton) || (isset($phepton) && $phepton->remain_of_number < $checkLeave->total_day_leave)){
                    return $this->responseError( __('xin_hrsale_leave_quota_completed'), 400);
                }
            }
        }
        if ($leave = $this->leave->update($id, ['status'=> $request->get('approve')])) {
            $title = ($leave->status == 2)?'Đơn xin nghỉ được chấp nhận':'Đơn xin nghỉ bị từ chối';
            $module = array('name'=>'leave','id'=>$id);
            $array_user_id = array($leave->employee_id);
            $notifi = (new NotificationRepository())->add_notification($title, $module, $array_user_id);
            return $this->responseSuccess(__("update_success"));
        }
        return $this->responseError(__("update_fail"));
    }

    public function updateIsSalary(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_update"));
        }
        if ($this->leave->update($id, ['is_salary'=> $request->get('is_salary')])) {
            return $this->responseSuccess(__("update_success"));
        }
        return $this->responseError(__("update_fail"));
    }

    public function delete(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->leave->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
}
