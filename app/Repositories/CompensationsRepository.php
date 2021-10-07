<?php
namespace App\Repositories;

use App\Jobs\CheckAttendanceStaff;
use App\Models\Compensations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SlackNotification;
use Illuminate\Support\Facades\DB;
use App\Jobs\Compensations as CompensationJob;
use App\Models\AttendanceTime;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;

class CompensationsRepository extends Repository
{
    public function getModel(): string
    {
        return Compensations::class;
    }

    public function createCompensations($datas) {
        try {
            DB::beginTransaction();
            foreach($datas as $data) {
                $compensation = $this->create($data);
                $this->notifyToSlack($data['compensation_date'], $compensation->compensation_id);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    protected function notifyToSlack($date, $id) {
        $name = Auth::user()->getFullname();
        $title = __(":name request compensations on date: :date", [
            'name' => $name,
            'date' => $date
        ]);
        $modules = array('name'=> 'compensations','id'=>$id);
        $array_user_id = (config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION'))?explode(',', config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION')):array();
        (new NotificationRepository())->add_notification($title, $modules, $array_user_id);
    }

    public function getListCompensations($paginateConfig, $date = null){
        $user_id = Auth::user();
//        $query = $this->model->where('employee_id', $user_id->user_id);


        if ($user_id->isAdmin()){
            $query = $this->model->with(['employee'])->orderBy('compensation_id', 'desc');
        }else{
            $query = $this->model->where('employee_id', $user_id->user_id);
        }
        if($date) {
            $dates = Carbon::createFromFormat("d-Y", $date);
            $query->whereYear('created_at', $dates->format('Y'))
                  ->whereMonth('created_at', $dates->format('d'));
        }
        // date

        $compensations = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $compensations;
    }
    public function checkTimeDuplicate($time, $status){
        $time_duplicate = [];
        foreach ($time as $key => $checkTime){
            $query = $this->model->where('compensation_date', date('Y-m-d', strtotime($checkTime)))
                ->where('employee_id',Auth::id())
                ->whereIn('compensation_status', ['0', '1'])
                ->get();
            if(count($query) > 0){
                $data_type = [];
                foreach ($query as $item){
                    $data_type[] = $item->compensation_type;
                }
                if (in_array(intval($status[$key]), $data_type) == true){
                     $time_duplicate['duplicateTime'][]= $checkTime;
                }elseif(intval($status[$key] == 1 && count($data_type) > 0) ){
                    $time_duplicate['no_create_full'][] = $checkTime;
                }
            }
        }
        return $time_duplicate;
    }
    /**
     * cập nhật đơn đơn đã duyệt
     */
    public function updateCompensationApprved($id){
//        $date_compensations = $request->date_compensations[0];
//        $reason = $request->reason[0];
//        $compensation_types = $request->value_checked[0];
        $compensation_info = $this->model->find($id);
        $dateTime = Carbon::createFromFormat("Y-m-d H:i:s", $compensation_info->compensation_date);
        $employee = Employee::find($compensation_info->employee_id);
        $atten_time = AttendanceTime::destroy($compensation_info->attendance_id);
        if ($atten_time == 1){
            $data = array(
                'compensation_status' => 0,
            );
            $response = $this->model->where('compensation_id', $id)->update($data);
            if ($response){
                dispatch(new CheckAttendanceStaff($compensation_info->employee_id, $dateTime->format('Y-m-d')));
                Artisan::call('timeChecker:check',[
                    'mode'       => 'monthly',
                    'businessId' => 1,
                    'companyId'  => $employee->company_id,
                    '-m'         => $dateTime->format('Y-m'),
                    '-e'         => $compensation_info->employee_id,
                ]);
                Artisan::call('payslipChecker:check',[
                    'businessId' => 1,
                    'companyId'  => $employee->company_id,
                    '-m'         => $dateTime->format('Y-m'),
                    '-e'         => $compensation_info->employee_id,
                ]);
            }else{
                return false;
            }
            return true;
        }else{
            return false;
        }
//            if ($atten_time == 1){
//                $data = [
//                    'compensation_date' => app('hrm')->getDateTimeConverter()->getForDBFromDate($date_compensations),
//                    'compensation_type' => $compensation_types,
//                    'compensation_status' => 0,
//                    'reason' => $reason
//                ];
//                $response = $this->update($id, $data);
//                if ($response){
//                    $dateTime = Carbon::createFromFormat("Y-m-d H:i:s", $compensation_info->compensation_date);
//                    CompensationJob::dispatch(
//                        $compensation_info->employee_id,
//                        $dateTime->format("Y-m-d"),
//                        $compensation_types,
//                        $response->compensation_id
//                    );
//                    DB::commit();
//                    return true;
//                }
//                DB::rollBack();
//                Log::info("Can't not update compensation  [{$id}]");
//                return false;
//            }else{
//                DB::rollBack();
//                Log::info("Update Fail compensation_id  [{$id}]");
//                return false;
//            }
    }
}
