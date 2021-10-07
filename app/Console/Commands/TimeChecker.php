<?php

namespace App\Console\Commands;

use App\Classes\Settings\SettingManager;
use App\Classes\TimeChecker\TimeChecker as Checker;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Exception;

class TimeChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timeChecker:check {mode} {businessId} {companyId} {--m|month=} {--e|employeeId=} {--d|date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    const MODE_DAILY = 'daily';
    const MODE_MONTHLY = 'monthly';

    protected $modes = [
        self::MODE_DAILY,
        self::MODE_MONTHLY
    ];

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
     * @return int
     */
    public function handle()
    {
        if ($this->matchedInput()) {
            $mode = $this->argument('mode');
            $businessId = $this->argument('businessId');
            $companyId = $this->argument('companyId');
            SettingManager::setBusinessId($businessId);
            if ($mode === 'daily') {
                $employee = $this->option('employeeId');
                $date = $this->option('date');
                if ($employee && $date) {
                    $checkDate = Carbon::createFromFormat("Y-m-d", $date);
                    $this->checkStaffOn($employee, $checkDate);

                } else {
                    $this->checkDaily($companyId);
                }
            } else {
                $this->checkMonthly($companyId);
            }
        }
    }

    protected function matchedInput() {
        $passed = true;
        $mode = $this->argument('mode');
        if (!in_array($mode, $this->modes)) {
            $this->error("Mode [$mode] not supported!");
            $passed = false;
        }
        $businessId = $this->argument('businessId');
        if (!$businessId) {
            $this->error("Missing argument [businessId]!");
        }
        $companyId = $this->argument('companyId');
        if (!$companyId) {
            $this->error("Missing argument  [companyId]!");
        }
        if ($mode === self::MODE_MONTHLY) {
            $month = $this->option('month');
            if (!$month) {
                $this->error("Option --month is required.");
            }
            if (!Carbon::canBeCreatedFromFormat($month, "Y-m")) {
                $this->error("month must be match with format 'year-month'");
                $passed = false;
            }
        } else if ($mode === self::MODE_DAILY) {
//            $employee = $this->option('employeeId');
//            $date = $this->option('date');
//            if (!($employee && $date) && ($employee || $date)) {
//                $this->error("arguments employee && date are required");
//            }
        }
        return $passed;
    }

    protected function checkStaffOn($employeeId, $date) {
        $employee = Employee::find($employeeId);
        Checker::check($employee, $date);
    }

    protected function checkDaily($companyId) {
        $date = now()->subDays();
        $checkDate = $this->option('date');
        if ($checkDate) {
            $checkDate = Carbon::createFromFormat("Y-m-d", $checkDate);
        }
        $date = $checkDate ? $checkDate : $date;
        return $this->checkOnDate($date, $companyId);
    }

    protected function checkMonthly($companyId) {
        $month = $this->option('month');
        $start = Carbon::createFromFormat("Y-m", $month)->startOfMonth();
        $end = Carbon::createFromFormat("Y-m", $month)->endOfMonth();

        $periods = CarbonPeriod::create($start, $end);
        $yesterday = now()->subDays(1);
        foreach($periods as $date) {
            if ($yesterday->lt($date)) {
                break;
            }
            $this->checkOnDate($date, $companyId);

        }
    }

    protected function checkOnDate($date, $companyId) {
        $activeEmployees = app()->make(EmployeeRepository::class)->getActiveEmployees($companyId);
        $this->info("Date: [{$date->format('Y-m-d')}]");
        foreach($activeEmployees as $employee) {
            Checker::check($employee, $date);
        }
    }
}
