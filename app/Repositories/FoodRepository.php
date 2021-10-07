<?php
namespace App\Repositories;

use App\Models\Food;

class FoodRepository extends Repository
{
    public function getModel(): string
    {
        return Food::class;
    }

    public function getFoodsByIds($ids) {
        return $this->model->whereIn('id', $ids)->get();
    }


}
