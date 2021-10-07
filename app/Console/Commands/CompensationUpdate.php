<?php

namespace App\Console\Commands;

use App\Classes\Settings\SettingManager;
use App\Classes\TimeChecker\PayslipChecker;
use App\Repositories\AttendanceTimeRequestRepository;
use Illuminate\Console\Command;
use App\Models\EmployeeTmpPayslip;
use App\Repositories\AttendanceTimeRepository;
use App\Jobs\CheckAttendanceStaff;
use App\Repositories\OfficeShiftRepository;
use App\Models\OfficeShift;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Compensations;

class CompensationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compensation:update {employee_id} {month} {compensation_type} {--id|compensation_id=} {is_online}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Carbon $date
     */
    private $date;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return array
     * type = 1 => đủ công
     * type = 2 => Nửa công sáng
     * type = 3 => Nửa công chiều
     */
    protected function compensation_type($date, $type, $employee_id){
        $work_date = $this->_getOfficeShift($employee_id, $date);
        if($type == 1){
            $data_type = [
                'clock_in' => $work_date['in_time'] != null ? $date.' '.$work_date['in_time']: '',
                'clock_out' => $work_date['in_time'] != null? $date.' '.$work_date['out_time'] : ''
            ];
        }elseif ($type == 2){
            $data_type = [
                'clock_in' => $work_date['in_time'] != null ? $date.' '.$work_date['in_time'] : '',
                'clock_out' => $work_date['in_time'] != null ? $date.' '.$work_date['lunch_break'] : ''
            ];
        }else{
            $data_type = [
                'clock_in'  => $work_date['in_time'] != null ? $date.' '.$work_date['leave_time'] : '',
                'clock_out' => $work_date['in_time'] != null ? $date.' '.$work_date['out_time'] : ''
            ];
        }
        return $data_type;
    }
    protected function _getOfficeShift($employee_id, $date){
        $getOfficeEmployee = Employee::where('user_id', $employee_id)->pluck('office_shift_id');
        $officeEmployee = app()->make(OfficeShiftRepository::class)->find($getOfficeEmployee[0]);
        return $this->officeShift($officeEmployee, Carbon::createFromFormat('Y-m-d', $date));
    }
    protected function officeShift(OfficeShift $officeShift, $date) {
        $day_name = strtolower($date->englishDayOfWeek);
        $column_time_in = sprintf("%s_in_time", $day_name);
        $column_time_out = sprintf("%s_out_time", $day_name);

        $resetTime = $this->_loadRestTime($date);
        $time_column = [
            'in_time'=>$officeShift->$column_time_in != "" ? $officeShift->$column_time_in.':00' : null,
            'out_time'=>$officeShift->$column_time_out != "" ? $officeShift->$column_time_out.':00' : null
        ];
        return array_merge($resetTime, $time_column);
    }
    protected function _loadRestTime($date)
    {
        $employee = app()->make(EmployeeRepository::class)->find($this->argument('employee_id'));
        $businessId = $employee->company->business_id;
        SettingManager::setBusinessId($businessId);
        $this->startRestTime = SettingManager::getOption("lunch_rest_start") ? SettingManager::getOption("lunch_rest_start")->setDateFrom($date) : null;
        $this->endRestTime = SettingManager::getOption("lunch_rest_end") ? SettingManager::getOption("lunch_rest_end")->setDateFrom($date) : null;
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->startRestTime);
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->endRestTime);
        return $time_column = [
            'lunch_break'=>$startTime->format('H:i:s', $startTime),
            'leave_time'=>$endTime->format('H:i:s', $endTime)
            ];
//            ;
    }
    protected function caculator_time($time_in, $time_out){
        $time_in = Carbon::createFromFormat('Y-m-d H:i:s', $time_in);
        $time_out = Carbon::createFromFormat('Y-m-d H:i:s', $time_out);
        $starttime = $time_in->format('H:i:s');
        $stoptime = $time_out->format('H:i:s');
        $diff = (strtotime($stoptime) - strtotime($starttime));
        $total = $diff/60;
        return sprintf("%02d:%02d", floor($total/60), $total%60);
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employee_id = $this->argument('employee_id');
        $compensation_id = $this->option('compensation_id');
        $employee_info = Employee::find($employee_id);
        $date = $this->argument('month');
        $compensation_type =  $this->argument('compensation_type');
        $is_online = $this->argument('is_online');
        $type_date = $this->compensation_type($date, $compensation_type, $employee_id);
        $year_month = Carbon::createFromFormat('Y-m-d',$date);
        $attendanceTime_data = [
            'attendance_date' => $date,
            'employee_id' =>  $employee_id,
            'clock_in' => $type_date['clock_in'],
            'fixed' => 1,
            'clock_out' => $type_date['clock_out'],
            'time_late'=> $type_date['clock_in'],
            'early_leaving'=> $type_date['clock_out'],
            'clock_in_ip_address' => '171.229.221.204',
            'clock_out_ip_address' => '171.229.221.204',
            'clock_in_latitude' =>  21.036596,
            'clock_out_latitude' =>  21.036596,
            'clock_in_longitude' =>  105.798049,
            'clock_out_longitude' =>  105.798049,
            'attendance_status' => 'Present',
            'clock_in_out' => 0,
            'overtime' => $type_date['clock_out'],
            'total_work' => $this->caculator_time($type_date['clock_in'], $type_date['clock_out']),
            'total_rest'=> 0,
            'is_online' => $is_online,
            'wages_type' => $employee_info['wages_type']
        ];
//        dd($attendanceTime_data);
//        dd($year_month->format('Y-m'), $employee_id, $employee_info->company_id);
        if ($create_attendance = app()->make(AttendanceTimeRepository::class)->create($attendanceTime_data)){
            Log::info("Start update attendance daily for [{$employee_id}] at [{$date}]");
            Compensations::where('compensation_id', $compensation_id)->update(['attendance_id'=>$create_attendance->time_attendance_id]);
            dispatch(new CheckAttendanceStaff($employee_id, $date));
            Artisan::call('timeChecker:check',[
                'mode'       => 'monthly',
                'businessId' => 1,
                'companyId'  => $employee_info->company_id,
                '-m'         => $year_month->format('Y-m'),
                '-e'         => $employee_id,
            ]);
            Artisan::call('payslipChecker:check',[
                'businessId' => 1,
                'companyId'  => $employee_info->company_id,
                '-m'         => $year_month->format('Y-m'),
                '-e'         => $employee_id,
            ]);
        }else{
            $this->error('Could not be initialized!');
        }
        return 0;
    }
}
