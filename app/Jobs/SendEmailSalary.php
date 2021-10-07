<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class SendEmailSalary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * param string
     */
    private $list_payslip_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list_payslip_id)
    {
      
        $this->list_payslip_id = $list_payslip_id;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('sendMail:payroll',[
            'list_payslip_id' => $this->list_payslip_id,
        ]);
    }
}
