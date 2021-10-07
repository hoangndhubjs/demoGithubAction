<?php
namespace App\Repositories;

use App\Models\AdvanceSalaries;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvanceSalariesRepository extends Repository
{
    public function getModel(): string
    {
        return AdvanceSalaries::class;
    }
    public function updateAdvance($datas_id, $status) {
        try {
            DB::beginTransaction();
            $this->model->where('status', 0)->whereIn('advance_salary_id', $datas_id)->update($status);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function exitsMonthAdvance($user_id, $date){
        $checkEmty = $this->model->where('employee_id', $user_id)->where('month_year', $date)->first();
        if ($checkEmty){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Các lần user ứng lương trong tháng
     */
    public function getAdvancedUser($user_id, $date){
        $advance_user = $this->model->where('employee_id', $user_id)
            ->where('month_year', $date)
            ->where('status', 1)
            ->get();
        $money = array(
            'list_advance' => [],
            'total' => 0,
        );
        foreach ($advance_user as $advanced){
            $money['list_advance'][] = $advanced->advance_amount;
            $money['total'] += $advanced->advance_amount;
        }
        return $money;
    }
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->status === 0 && $result->update($attributes);
            return $result;
        }

        return false;
    }


//    public function getAseetUser($paginateConfig) {
//        $user_info = Auth::user();
//        $query = Asset::with(['employeeAsset','companyAsset','categoryAsset'])->where('employee_id', $user_info->user_id)->where('company_id', $user_info->company_id);
//        $listAsset = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
//        return $listAsset;
//    }
}
