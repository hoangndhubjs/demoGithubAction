<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Compensations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employee_id;
    public $compensation_date;
    public $compensation_type;
    public $compensation_id;
    public $type_of_work;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee_id, $compensation_date, $compensation_type, $compensation_id, $type_of_work)
    {
        $this->employee_id = $employee_id;
        $this->compensation_date = $compensation_date;
        $this->compensation_type = $compensation_type;
        $this->compensation_id = $compensation_id;
        $this->type_of_work = $type_of_work;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Starting update compensation (call command) [{$this->employee_id}] [{$this->compensation_date}]");
        Artisan::call('compensation:update',[
            'employee_id'       =>$this->employee_id,
            'month'             =>$this->compensation_date,
            'compensation_type' =>$this->compensation_type,
            '-id'               =>$this->compensation_id,
            'is_online'         =>$this->type_of_work
        ]);
    }
}
