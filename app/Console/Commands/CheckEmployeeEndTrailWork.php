<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class CheckEmployeeEndTrailWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:EmployeeEndTrailWork';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', (strtotime('-1 day', strtotime($today))));
        $employee = Employee::where('wages_type', 2)->where('is_active', 1)->where('end_trail_work', $yesterday)->get();

        foreach ($employee as $value) {
            Employee::where('user_id', $value->user_id)->update(['wages_type' => 1]);
            echo 'Success\n';
        }
    }
}
