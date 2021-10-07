<?php

namespace App\Jobs;

use App\Classes\Settings\SettingManager;
use App\Classes\TimeChecker\TimeChecker;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckAttendanceStaff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employeeId;
    public $checkDate;

    /**
     * Create a new job instance.
     *
     * @param $employeeId
     * @param $checkDate
     */
    public function __construct($employeeId, $checkDate)
    {
        $this->employeeId = $employeeId;
        $this->checkDate = $checkDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkDate = Carbon::createFromFormat("Y-m-d", $this->checkDate);
        $employee = app()->make(EmployeeRepository::class)->find($this->employeeId);
        if (!$employee) {
            Log::error("Employee [{$this->employeeId}] not found! In CheckAttendanceStaff");
        }
        $businessId = $employee->company->business_id;
        SettingManager::setBusinessId($businessId);
        TimeChecker::check($employee, $checkDate);
    }
}
