<?php

namespace App\Http\Controllers\Overtime;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\OvertimeRequest;
use App\Models\Employee;
use App\Models\Holiday;
use App\Repositories\AttendanceTimeRequestRepository;
use App\Repositories\HolidayRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OfficeShiftRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    use DatatableResponseable;

    private $attendance;

    public function __construct(AttendanceTimeRequestRepository $attendance)
    {
        $this->attendance = $attendance;
    }

    public function index() {
        $page_title = __('dashboard_overtime');
        $page_description = __('xin_overtime_request');
        if(Auth::user()->isAdmin()){
            return view('pages.overtime.admin_list', compact('page_title', 'page_description'));
        } else{
            return view('pages.overtime.list', compact('page_title', 'page_description'));
        }
    }

    /**
     * danh sách yêu cầu làm thêm giờ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listOvertime(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $overtimes = $this->attendance->getOvertimeRequestByUserId($paginateConfig);
        return $this->makeDatatableResponse($overtimes, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * Xóa yêu cầu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOvertime(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->attendance->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    /**
     * Tạo form thêm mới và cập nhật yêu cầu
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createOvertimeForm(Request $request) {
        $id = $request->get('id', null);
        $overtime = null;
        if($id){
            $type = 'updated';
            $overtime = $this->attendance->find($id);
        } else {
            $type = 'created';
        }
        return view('pages.overtime.form_modal', compact('overtime', 'type'));
    }

    /**
     * Cập nhật và thêm mới yêu cầu
     *
     * @param OvertimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateRequestOvertime(OvertimeRequest $request){
        // kiểm tra ca làm việc
        $office_shift = (new OfficeShiftRepository())->find(Auth::user()->office_shift_id);
        if(!isset($office_shift)){
            return $this->responseError( __('xin_office_shift_not_assigned'));
        }
        //Lấy thứ trong tuần, ví dụ thứ ba
        $shift = strtolower(date('l', strtotime($request->request_date)));
        //Lấy thời gian làm việc trong ngày
        $in = $shift.'_in_time';
        $out = $shift.'_out_time';
        $office_shift_start = $office_shift->$in;
        $office_shift_end = $office_shift->$out;
        // Thêm OT vào ngày lễ
        $date = date('Y-m-d', strtotime($request->request_date));
        //Kiem tra ngay them gio co phai holidays
        $holidays = app()->make(HolidayRepository::class)->getHolidaysAt($date);

        if(
            ((strtotime($office_shift_start) < strtotime($request->request_clock_in) && strtotime($request->request_clock_in) < strtotime($office_shift_end))||
            (strtotime($office_shift_start) < strtotime($request->request_clock_out) && strtotime($request->request_clock_out) < strtotime($office_shift_end))||
            (strtotime($request->request_clock_in) < strtotime($office_shift_start) && strtotime($request->request_clock_out) > strtotime($office_shift_end)))&&
            ($holidays->count() == 0)
        ){
            return $this->responseError( __('xin_request_in_office_shift'));
        }
        $clock_in = date("G:i", strtotime($request->request_clock_in));
        $clock_out = date("G:i", strtotime($request->request_clock_out));

        $clock_in = date('Y-m-d', strtotime($request->request_date)).' '.$clock_in.':00';
        $clock_out = date('Y-m-d', strtotime($request->request_date)).' '.$clock_out.':00';
        // => Lấy ra ngày tháng năm giờ bắt đầu và kết thúc
        //total work
        $total_work_cin =  new \DateTime($clock_in);
        $total_work_cout =  new \DateTime($clock_out);

        $interval_cin = $total_work_cout->diff($total_work_cin);
        $hours_in   = $interval_cin->format('%h');
        $minutes_in = $interval_cin->format('%i');
        $total_work = $hours_in .":".$minutes_in;
        $total_work_in_minutes = (intval($hours_in) * 60) + intval($minutes_in);
        // $total_work_in_minutes => Lấy ra tổng số phút làm thêm giờ
        if($holidays->count() > 0){
            $total_work_in_minutes =  $total_work_in_minutes * 3;
        }elseif($shift == 'sunday') {
            $total_work_in_minutes =  $total_work_in_minutes * 2;
        }else {
            $total_work_in_minutes = $total_work_in_minutes * 1.5;
        }

        $data = array(
            'company_id' => Auth::user()->company_id,
            'employee_id' => Auth::user()->user_id,
            'request_date' => ($request->request_date)?date('Y-m-d', strtotime($request->request_date)):'',
            'request_date_request' => ($request->request_date)?date('Y-m', strtotime($request->request_date)):'',
            'request_clock_in' => $clock_in,
            'request_clock_out' => $clock_out,
            'total_hours' => $total_work,
            'project_no' => '',
            'purchase_no' => '',
            'task_name' => '',
            'request_reason' => ($request->reason)?$request->reason:'',
            'is_approved' => 1,
            'total_hour_minutes' => $total_work_in_minutes,
            'total_tw_hour_minutes' => 0,
        );

        $wages_type = Auth::user()->wages_type;
        $end_trail_work = Auth::user()->end_trail_work;
        // kiểm tra request trùng

        if(($wages_type == 1 && $clock_in <= $end_trail_work) || $wages_type == 2){
            $data['total_tw_hour_minutes'] = $total_work_in_minutes;
            $data['total_hour_minutes'] = 0;
        }

        if ($id = $request->get('id')) {
            $checkValidateTime = $this->attendance->validateTime($data['request_date'], $clock_in, $clock_out, $id);
            if($checkValidateTime){
                return $this->responseError( __('xin_check_overtime_error'));
            }
            // Xét chính thức hay thử việc
            $overtime = $this->attendance->update($id, $data);
        } else {
            $checkValidateTime = $this->attendance->validateTime($data['request_date'], $clock_in, $clock_out);
            if($checkValidateTime){
                return $this->responseError( __('xin_check_overtime_error'));
            }
            $overtime = $this->attendance->create($data);
        }

        return $this->responseSuccess($overtime);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function detail($id){
        if($this->attendance->find($id)){
            $edata = array(
                'is_notify' => 0,
            );
            $type = (new NotificationRepository())->updateNotificationRecord($edata, $id, Auth::user()->user_id,'request_ot');
        }
        return redirect(route('overtime_request.list'));
    }

    public function listOvertimeAdmin(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $overtimes = $this->attendance->getOvertimeRequestAdmin($paginateConfig, $request);
        return $this->makeDatatableResponse($overtimes, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function approveRequest(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_update"), 400);
        }

        if ($leave = $this->attendance->update($id, ['is_approved'=> $request->get('approve')])) {
            return $this->responseSuccess(__("update_success"));
        }
        return $this->responseError(__("update_fail"));

    }
}
