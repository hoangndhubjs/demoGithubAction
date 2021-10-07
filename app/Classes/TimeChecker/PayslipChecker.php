<?php
namespace App\Classes\TimeChecker;

use App\Models\Employee;
use App\Models\EmployeeTmpPayslip;
use App\Repositories\AttendanceDailyRepository;
use App\Repositories\SalaryPayslipsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayslipChecker
{
    /**
     * @var array
     */
    private $employeeIds;
    /**
     * @var Carbon
     */
    private $month;

    public function __construct(Carbon $month, $employeeIds = array()) {
        $this->employeeIds = $employeeIds;
        $this->month = $month;
        $this->makeTmpPayslip();
    }

    public static function check(Carbon $month, $employeeIds = array()) {
        return new PayslipChecker($month, $employeeIds);
    }

    public function makeTmpPayslip()
    {
        $dailyAttendances = app()->make(AttendanceDailyRepository::class)->getPayslipSummaryOfMonth($this->month, $this->employeeIds);
        DB::transaction(function () use($dailyAttendances) {
            EmployeeTmpPayslip::where([
                'month' => $this->month->format("Y-m")
            ])->whereIn('employee_id', $this->employeeIds)->delete();
            foreach($dailyAttendances as $attendance) {
                $data = array(
                    "employee_id" => $attendance->employee_id,
                    "employee_type" => $attendance->employee_type ?? 0,
                    "ngay_cong" => $attendance->ngay_cong,
                    "phut_di_muon" => $attendance->phut_di_muon,
                    "phut_ve_som" => $attendance->phut_ve_som,
                    "lam_nua_ngay" => $attendance->nua_cong,
                    "ngay_nghi" =>  $attendance->ngay_nghi,
                    "nghi_nua_ngay" => $attendance->nghi_nua_ngay,
                    "ngay_le" => $attendance->ngay_le,
                    "overtime" => $attendance->overtime,
                );
                EmployeeTmpPayslip::updateOrCreate([
                    'employee_id' => $attendance->employee_id,
                    'month' => $this->month->format("Y-m"),
                    'employee_type' => intval($attendance->employee_type) ?? 0
                ], $data);
            }
        });
    }
}
