<?php

namespace App\Console\Commands;

use App\Classes\Settings\SettingManager;
use App\Mail\sendPayroll;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\SalaryPayslip;
use Illuminate\Support\Facades\App;
class SendEmailPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMail:payroll {list_payslip_id} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $whitelist = [
        'dev02.hqgroup@gmail.com',
        'dev01.hqgroup@gmail.com',
        'sp05.hqgroup@gmail.com',
        'sp01.hqgroup@gmail.com',
        'sp04.hqgroup@gmail.com',
        'hangdp@hqgroups.vn',
        'dinhphuonghang98@gmail.com',
        'phamtrieu.hqgroup@gmail.com'
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

        $memberArray = explode(',', $this->argument('list_payslip_id'));
        foreach ($memberArray as $member_id){
            $getOneSalary  = SalaryPayslip::find($member_id);
            $receiveEmail = $getOneSalary->employeeSalary->email;
            if ($this->canSendMail($receiveEmail)) {
                $employee = app()->make(EmployeeRepository::class)->find($getOneSalary->employee_id);
                $businessId = $employee->company ? $employee->company->business_id : 1;
                SettingManager::setBusinessId($businessId);
                Mail::to($receiveEmail)->send(new sendPayroll($member_id));
            }
        }
        return 0;
    }

    private function canSendMail($email) {
        if (!App::environment('production')) {
            return in_array(strtolower($email), $this->whitelist);
        }
        return true;
    }
}
