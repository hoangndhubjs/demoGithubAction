<?php

namespace App\Console\Commands;

use App\Classes\Settings\SettingManager;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Classes\TimeChecker\PayslipChecker as Checker;

class PayslipChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payslipChecker:check {businessId} {companyId} {--m|month=} {--e|employeeId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
    Calculate for payslip
        payslipChecker:check <busineessId> <companyId> [-m|--month=] [--e|employeeId=]
    - Calc for company current month
        payslipChecker:check 1 1
    - Calc for company in specific month
        payslipChecker:check 1 1 -m2020-10
    - Calc for an employee
        payslipChecker:check 1 1 -m2020-10 -e143
    ';

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
        $businessId = $this->argument('businessId');
        SettingManager::setBusinessId($businessId);
        $companyId = $this->argument('companyId');
        $month = $this->option('month');
        if (!$month) {
            $month = now()->subDay();
        } else {
            $month = Carbon::createFromFormat("Y-m-d", $month.'-01');
        }
        $employeeId = $this->option('employeeId');
        $employeeIds = app()->make(EmployeeRepository::class)->getEmployeeIds($companyId);
        if ($employeeId) {
            if (!in_array($employeeId, $employeeIds)) {
                $this->error("Employee [$employeeId] not found!");
                return 0;
            }
            Checker::check($month, [$employeeId]);
        } else {
            Checker::check($month, $employeeIds);
        }
        return 0;
    }
}
