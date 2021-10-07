<?php
namespace App\Repositories;

use App\Models\OfficeShift;
use Illuminate\Support\Facades\Auth;

class OfficeShiftRepository extends Repository
{
    public function getModel(): string
    {
        return OfficeShift::class;
    }

    public function getOfficeShift($paginateConfig) {
        $user_info = Auth::user();

        if($user_info->isAdmin()){
            $query = OfficeShift::where('company_id', $user_info->company_id);
		} else {
            $query = OfficeShift::where('office_shift_id', $user_info->office_shift_id);
        }

        $office_shift = $query->orderBy('default_shift','desc')->with(['company'])->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $office_shift;
    }

    public function getOfficeShiftOf($office_shift_id) {
        return $this->model->where('office_shift_id', $office_shift_id)->first();
    }

    public function getDefaultShift() {
        return $this->model->where('default_shift', 1)->first();
    }
    public function getOfficeShiftByCompany($company_id, $name=null){
        $results = $this->model->where('company_id', $company_id);
        if($name != null){
            $results->where('shift_name', $name);
        }
        $results = $results->get();

        return $results;
    }

}

