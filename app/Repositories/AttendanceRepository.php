<?php
namespace App\Repositories;

use App\Models\AttendanceTime;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceRepository extends Repository
{
    private $checkinTime;
    private $checkoutTime;

    public function getModel(): string
    {
        return AttendanceTime::class;
    }
    
    public function getAttendancesOn($paginateConfig, $user_id, $from, $to) {
        $condition = [
            'employee_id' => $user_id
        ];
        $query = $this->model->with(['employee'])->where($condition)
                ->where('attendance_date', '>=', $from)
                ->where('attendance_date', '<=', $to);
        return $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
    }

    public function getTimeSheetByDay($paginateConfig, $request){
        $is_active = 1;
        $date = date('Y-m-d');

        if(isset($request->date)){
            $date = date('Y-m-d', strtotime($request->date));
        }
        $shift = strtolower(date('l', strtotime($date)));
        $in = $shift.'_in_time';
        $out = $shift.'_out_time';

        $query = Employee::with(['company'])->where('company_id', Auth::user()->company_id)
            ->where('is_active', $is_active);

        if (isset($request->employee_name)){
            $query->where(function($q) use ($request){
                $q->whereRaw("concat(first_name, ' ', last_name) like '%$request->employee_name%' ");
                $q->orWhereRaw("concat(last_name, ' ', first_name) like '%$request->employee_name%' ");
                $q->orWhere("employee_id", "like", '%'.$request->employee_name.'%');
            });
        }
        $query->groupBy('employee_id');

        $listEmployee = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        foreach ($listEmployee as $employee){
            $this->getAttendanceByDay($employee->user_id, $date);
            $employee->checkinTime = ($this->checkinTime)?$this->checkinTime->format("H:i"):null;
            $employee->checkoutTime = ($this->checkoutTime)?$this->checkoutTime->format("H:i"):null;
            $employee->attendance_date = date('d-m-Y', strtotime($date));

            if(isset($this->checkinTime) && isset($this->checkoutTime)){
                $office_shift = (new OfficeShiftRepository())->find($employee->office_shift_id);
                if(!isset($office_shift)){
                    $employee->dimuon = null;
                    $employee->vesom = null;
                } else {
                    $office_shift_start = $office_shift->$in;
                    $office_shift_end = $office_shift->$out;
                    $employee->dimuon = '00:00';
                    $employee->vesom = '00:00';
                    if(strtotime($office_shift_start) < strtotime($employee->checkinTime)){
                        // phut ve muon
                        $employee->dimuon = gmdate('H:i', (strtotime($employee->checkinTime) - strtotime($office_shift_start)));
                    }
                    if(strtotime($office_shift_end) > strtotime($employee->checkoutTime)){
                        // phut ve som
                        $employee->vesom = gmdate('H:i', (strtotime($office_shift_end) - strtotime($employee->checkoutTime)));
                    }
                }
            } else {
                $employee->dimuon = null;
                $employee->vesom = null;
            }
        }
        return $listEmployee;
    }

    public function getAttendanceByDay($id, $date){
        $attendanceTimeRepo = app()->make(AttendanceTimeRepository::class);
        $attendanceTimes = $attendanceTimeRepo->getAttendanceTimesOf($id, $date);
        $min = $max = 0;
        foreach ($attendanceTimes as $log) {
            $check_in = strtotime($log->clock_in);
            $check_out = $log->clock_out ? strtotime($log->clock_out) : $check_in;
            $min === 0 && $min = $check_in;
            $max === 0 && $max = $check_out;
            $check_in < $min && $min = $check_in;
            $check_out > $max && $max = $check_out;
        }
        if($min > 0){
            $this->checkinTime = Carbon::createFromTimestamp($min);
        } else {
            $this->checkinTime = null;
        }
        if($max > 0){
            $this->checkoutTime = Carbon::createFromTimestamp($max);
        } else {
            $this->checkoutTime = null;
        }
        /*$min > 0 && $this->checkinTime = Carbon::createFromTimestamp($min);
        $max > 0 && $this->checkoutTime = Carbon::createFromTimestamp($max);*/
    }

    public function getTimeSheetByEmployee($paginateConfig, $request){
        $query = $this->model->with(['employee']);
        if($request->date){
            $query->where('attendance_date', date('Y-m-d', strtotime($request->date)));
        }
        if($request->employee_name){
            $query->where('employee_id', $request->employee_name);
        }

        return $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
    }
}