<?php

namespace App\Http\Controllers\Compensations;

use App\Classes\PaginateConfig;
use App\Classes\Settings\SettingManager;
use App\Http\Controllers\Controller;
use App\Repositories\NotificationRepository;
use App\Traits\DatatableResponseable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CompensationsRepository;
use App\Console\Commands\CompensationUpdate;
use App\Jobs\Compensations;
use Illuminate\Support\Facades\Log;
use \App\Models\SalaryPayslip;
use App\Repositories\LeaveRepository;

class CompensationsController extends Controller
{
    use DatatableResponseable;
    private $compensations;
    private $payslip;
    private $leave;
    public function __construct(CompensationsRepository $compensations, SalaryPayslip $payslip, LeaveRepository $leave)
    {
        $this->compensations = $compensations;
        $this->payslip = $payslip;
        $this->leave = $leave;
    }
    public function list(){
        $page_title = 'Bù công';
        return view('pages.compensation.list', compact('page_title'));
    }
    public function listCompensations(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $compensations = $this->compensations->getListCompensations($paginateConfig, $request->month);
        return $this->makeDatatableResponse($compensations, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    public function createCompensationForm(Request $request) {
        $id = $request->get('id', null);
        $compensation = null;
        if($id){
            $compensation = $this->compensations->find($id);
        }
        return view('pages.compensation.form_modal',compact('compensation'));
    }
    public function deleteCompensations(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->compensations->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }else{
            return $this->responseError(__("delete_fail"));
        }

    }
    public function add_compensations(Request $request){

        $data_compensations_date = $request->date_compensations; // Ngày bù công
        $reasons = $request->reason; // lý do
        $data_compensations = $request->value_checked; // Loại bù công
        $type_of_work = $request->type_of_work; // Loại công online, offline

        $data_error = [];
        // check duplicate date compensations
        $getTimeDuplicate = $this->compensations->checkTimeDuplicate($data_compensations_date, $data_compensations);
        if(count($getTimeDuplicate) > 0 ){
            return $getTimeDuplicate;
        }else{
            $data_insert = [];
            foreach ($data_compensations_date as $key => $date){
                $data_insert[] = [
                    'compensation_date' => app('hrm')->getDateTimeConverter()->getForDBFromDate($date),
                    'employee_id' => Auth::id(),
                    'compensation_type' => $data_compensations[$key],
                    'reason' => $reasons[$key],
                    'type_of_work' => $type_of_work,
                ];
            }

            if ($this->compensations->createCompensations($data_insert)) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else{
                return $this->responseError(__('create_compensation_error'));
            }
        }
    }
    public function updateCompensations(Request $request){
        $compensation_id = $request->id;
        $compensation_status = $request->status; // 1 => duyệt , 2 => không duyệt
        $compensation_info = $this->compensations->find($compensation_id);
        $dateTime = Carbon::createFromFormat("Y-m-d H:i:s", $compensation_info->compensation_date);
        $data_confirm = '';
        if ($compensation_status == 2){
            $data_confirm = $request->reason_confirm ? $request->reason_confirm : '';
        }
        $data = [
            'compensation_status' => $compensation_status,
            'comment_compensation' => $data_confirm
        ];
          $response = $this->compensations->update($compensation_id, $data);
          $type_of_work = 2;
          if ($response->type_of_work == 'on') {
              $type_of_work = 1;
          } elseif ($response->type_of_work == 'off') {
              $type_of_work = 0;
          }

          if ($response){
              if($compensation_status == 1){
                  Log::info("Starting compension for [{$compensation_info->employee_id}] at [{$dateTime->format("Y-m-d")}]");
                  Compensations::dispatch(
                      $compensation_info->employee_id,
                      $dateTime->format("Y-m-d"),
                      $compensation_info->compensation_type,
                      $response->compensation_id,
                      $type_of_work
                  );
              }
              return $this->responseSuccess(__('xin_theme_success'));
          }else{
              return $this->responseError(__("update_error"));
          }
    }
    /**
     * Xoa va cap chay lai bang attentime va attentime_daily cap nhat trang thai don bu cong
     */
    public function setApprovedCompensation(Request $request){
//        dd($request->all());
        // cap nhat don da duyet
        if($id = $request->id){
            $update_apr = $this->compensations->updateCompensationApprved($id);
            if ($update_apr == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else{
                return $this->responseError(__('create_compensation_error'));
            }
        }
    }
    /**
     * kiểm tra tháng chọn bù côncg đã thanh toán hay chưa
     * kiểm tra ngày chọn bù công có đơn xin nghỉ ko
     */
    public function compensationsCheck(Request $request){
        $dates = Carbon::createFromFormat('Y-m-d', $request->date);
        $date = $dates->format('Y-m');
        $user_id  = Auth::id();
        $checkExits = $this->payslip->selectRaw('status')
            ->where('employee_id', $user_id)
            ->where('status', 2)
            ->where('salary_month', $date)->first();
        $checkLeave = $this->leave->leaveRequestCompensation($user_id, $request);
        if (count($checkLeave) > 0) $reponse = $checkLeave;
        else $reponse = $checkExits;

        return response()->json($reponse);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function detail($id){
        if($this->compensations->find($id)){
            $edata = array(
                'is_notify' => 0,
            );
            $type = (new NotificationRepository())->updateNotificationRecord($edata, $id, Auth::user()->user_id,'compensations');
        }
        return redirect(route('compensations.list'));
    }
}
