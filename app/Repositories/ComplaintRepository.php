<?php
namespace App\Repositories;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintRepository extends Repository
{
    public function getModel(): string
    {
        return Complaint::class;
    }

    /**
     * @param $paginateConfig
     * @param null $status
     * @param null $user_id
     * @return mixed
     */
    public function getComplaintByUserId($paginateConfig, $status = null, $user_id = null){
        if(!$user_id){
            $user_id = Auth::id();
        }
        $query = $this->model->with(['company'])
            ->where('complaint_from', $user_id)
            ->where('company_id', Auth::user()->company_id);

        if($status != null || $status != ''){
            $query->where('status', $status);
        }

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $complaints = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        foreach($complaints as $complaint) {
            /*$complaint_against = @json_decode($complaint->complaint_against);
            if(gettype($complaint_against) != 'array'){
                $complaint->complaint_against = explode(',', $complaint->complaint_against);
            } else {
                $complaint->complaint_against = $complaint_against;
            }*/
            $against = (new EmployeeRepository())->getAgainstByIds($complaint->complaint_against)->keyBy('user_id');
            $complaint->against = $against;
        }

        return $complaints;
    }

    /**
     * @return mixed
     */
    public function getListStatus(){
        return $this->model->listStatus();
    }

}
