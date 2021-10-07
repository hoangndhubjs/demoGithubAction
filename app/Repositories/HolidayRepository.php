<?php
namespace App\Repositories;

use App\Models\Holiday;
use Carbon\Carbon;

class HolidayRepository extends Repository
{
    public function getModel(): string
    {
        return Holiday::class;
    }

    public function getHolidaysAt($date) {
        return $this->model->where('start_date', '<=', $date)
        ->where('end_date', '>=', $date)->where('is_publish', 1)->get();
    }

    public function getHoliday($paginateConfig){
        $query = $this->model;
        return $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
    }
}
