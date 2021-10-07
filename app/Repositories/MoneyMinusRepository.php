<?php
namespace App\Repositories;

use App\Models\MoneyMinus;
use Illuminate\Support\Facades\DB;

class MoneyMinusRepository extends Repository
{
    public function getModel(): string
    {
        return MoneyMinus::class;
    }

    public function getUserMoneyMinus($paginateConfig, $user_id) {

        $query = MoneyMinus::where('user_id', $user_id)->orderBy('option_minus', 'asc');

        $listMoneyMinus = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listMoneyMinus;
    }

    public function getTotalMinus($user_id, $date) {
        $getMinus = $this->model->where('user_id', $user_id)
            ->where(function ($sub) use($date) {
                $sub->where('year_month', $date)
                    ->orWhere('amount_option', 2);
            })
            ->sum('money');
        return $getMinus ? $getMinus : 0;
    }

    public function getMoneyMinus($user_id, $date) {
        return $this->model->where('user_id', $user_id)
            ->where(function ($sub) use($date) {
                $sub->where('year_month', $date)
                    ->orWhere('amount_option', 2);
            })->get();
    }
    /**
     * create data from detail_salary
     */
    public function create_data_minus($request){
        try {
            DB::transaction(function () use($request) {
                $get_data_plus = $request->data;
                $month = $request->month_salary;
                $employee_id = $request->employee_id;
                foreach ($get_data_plus as $key => $item_plus){
                    $data_plus = array(
                        "user_id" => $employee_id,
                        "title" => $item_plus[0],
                        "amount_option" => 1,
                        "money" => $item_plus[1],
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
