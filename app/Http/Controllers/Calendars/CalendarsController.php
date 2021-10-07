<?php

namespace App\Http\Controllers\Calendars;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Meeting;
use App\Repositories\MeetingsRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\CompanyRepository;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarsController extends Controller
{
    private $meeting;
    private $leave;
    private $leave_type;
    private $company;
    private $employee;
    public function __construct(
        MeetingsRepository $meeting,
        LeaveRepository $leave,
        LeaveTypeRepository $leave_type,
        CompanyRepository $company,
        EmployeeRepository $employee
    )
    {
        $this->meeting = $meeting;
        $this->leave = $leave;
        $this->leave_type = $leave_type;
        $this->company = $company;
        $this->employee = $employee;
    }

    public function index(){
        $listMeetings = $this->meeting->getAll();
        $page_title = 'Lịch';
        $page_description = '';
        $all_companies = $this->company->get_all_companies();
        $all_types = $this->leave_type->all_leave_types();
        return view('pages.calendars.list', compact('page_title', 'page_description','listMeetings','all_types','all_companies'));
    }
    public function get_employee_company(Request $request){
        $company_id = $request->get('company_id');
        $getMemberCompany = $this->employee->getEmployeesByCompany($company_id);
        return response()->json($getMemberCompany);
    }
    public function calendar_post(Request $request){

        $startDate = $request->get("startDate");
        $endDate = $request->get("endDate");
        $data_reponse = $this->meeting->getMeetingByUserRole($startDate, $endDate);
//        dd($data_reponse);
        $data_leave = $this->leave->getLeaveByUserRole($startDate, $endDate);

        $data_birthday_employee = $this->employee->getBirthdayCommingEmployee($startDate, $endDate);

        $merge = array_merge(array_merge($data_leave, $data_reponse), $data_birthday_employee);
        return response()->json($merge);
    }
    public function add_mettings(Request $request){
        $date_start = date("H:i", strtotime($request->meeting_time));
        $date_end = date("H:i", strtotime($request->meeting_end));
        $duplicateTime = $this->meeting->duplicateTime($date_start, $date_end,$request->meeting_date);
        if(count($duplicateTime) > 0){
             $data=array(
               'error_title'  =>'Thời gian bạn chọn đã có người đặt, vui lòng đặt lại',
                 'meetings'=> $duplicateTime,
             );
            return response()->json($data);
        }else{
            $data_insert = [
                'company_id' => $request->company_id,
                'employee_id' => $request->empolyee_id,
                'meeting_title' => $request->meeting_title,
                'meeting_date' => $request->meeting_date,
                'meeting_time' => $date_start,
                'meeting_end' => $date_end,
                'meeting_room' => $request->meeting_room,
                'meeting_color' => '#F64E60',
                'meeting_note' => $request->meeting_note,
            ];
            $createMeeting =  $this->meeting->create($data_insert);
            if($createMeeting){
                return $this->responseSuccess(__('xin_theme_success'));
            }else{
                return $this->responseError('Thất bại');
            }
        }
    }
    public function detail_calendar(Request $request){
        $meetings = $this->meeting->detail_meetings($request->meeting_id);
        return $meetings;
    }
    public function detail_leave(Request $request){
        $leave_id = intval($request->leave_id);
        $query = Leave::with(['employee','companyLeave','leave_type'])->where('leave_id', $leave_id)->get();
        return $query;
    }

}
