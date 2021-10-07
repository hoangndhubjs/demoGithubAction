<?php
namespace App\Repositories;

use App\Http\Controllers\Admin\Payrolls\PayrollController;
use App\Models\SalaryAllowance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllowanceRepository extends Repository
{
    public function getModel(): string
    {
        return SalaryAllowance::class;
    }
    /**
     * create data from detail_salary
     */
   public function create_data_plus($request){
       try {
           DB::transaction(function () use($request) {
               $get_data_plus = $request->data;
               $month = $request->month_salary;
               $employee_id = $request->employee_id;
               foreach ($get_data_plus as $key => $item_plus){
                   $data_plus = array(
                       "allowance_title" => $item_plus[0],
                       "allowance_amount" => $item_plus[1],
                       "is_allowance_taxable" => 0, //default = 0
                       "amount_option" => 1,//default = 1
                       "employee_id" => $employee_id,
                       "year_month" => $month
                   );
                  $this->model->create($data_plus);
               }
           });
           $update_payslip = app()->make(SalaryManagerRepository::class)->payroll_salary_all($request);
           if ($update_payslip->original['status'] === "success"){
               DB::commit();
               return response()->json(array('status'=>true));
           }else{
               DB::rollBack();
               return response()->json(array('status'=>false));
           }
       } catch (\Exception $e) {
           DB::rollBack();
           return response()->json(array('status'=>false));
       }
   }
}
