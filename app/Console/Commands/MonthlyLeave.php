<?php

namespace App\Console\Commands;

use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeesPheptonRepository;
use Illuminate\Console\Command;

class MonthlyLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlyLeave {companyId}';

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
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        // lấy danh sách user active - chính thức
        $companyId = $this->argument('companyId');
        $employees = app()->make(EmployeeRepository::class)->officialEmployee($companyId);
        if(isset($employees) && $employees->count() > 0){
            foreach ($employees as $employee){
                echo $employee->user_id.'/n';
                $data = [
                    'employee_id' => $employee->user_id,
                    'leave_type_id'	=> 5,
                    'grant_of_number' => 1,
                    'used_of_number' => 0,
                    'remain_of_number' => 1,
                    'year' => date('Y'),
                    'expiration_date' => date('Y-m-d', strtotime('12/31'))
                ];
                $checkPhepTon = app()->make(EmployeesPheptonRepository::class)->checkPhepTon($data);
                if($checkPhepTon){
                    unset($data['used_of_number']);
                    $data['grant_of_number'] = $checkPhepTon->grant_of_number + 1;
                    $data['remain_of_number'] = $checkPhepTon->remain_of_number + 1;
                    app()->make(EmployeesPheptonRepository::class)->update($checkPhepTon->id, $data);
                } else {
                    app()->make(EmployeesPheptonRepository::class)->create($data);
                }
            }
        }
        echo "done";
        return 0;
    }
}
