<?php
namespace App\Repositories;

use App\Models\FoodMenu;

class FoodMenuRepository extends Repository
{
    public function getModel(): string
    {
        return FoodMenu::class;
    }

    protected function getPeriod() {
        $lunch_confirmed_hour = config('constants.ENDING_OF_MORNING');
        return time() < strtotime($lunch_confirmed_hour) ? FoodMenu::TYPE_LUNCH : FoodMenu::TYPE_DINNER;
    }

    public function getAvailableMenuToday() {
        $period = $this->getPeriod();
        $date = date('Y-m-d');
        return $this->getMenuOn($date, $period);
    }

    public function getMenuOn($date, $period) {
        $conditions = array(
            'create_date' => $date,
            'an_trua' => $period,
            'status' => FoodMenu::YET_PEGGED
        );
        $query = $this->model->where($conditions);
        $menu = $query->first();
        if ($menu) {
            $menu->mon_chinh = @json_decode($menu->mon_chinh);
            $menu->mon_phu = @json_decode($menu->mon_phu);
            $menu->mon_rau = @json_decode($menu->mon_rau);
            $food_ids = array_merge($menu->mon_chinh, $menu->mon_phu, $menu->mon_rau);
            $foods = (new FoodRepository())->getFoodsByIds($food_ids)->groupBy('type');
            $menu->foods = $foods;
        }
        return $menu;
    }

    public function getMenuDishes($paginateConfig, $create_date = null) {
        $query = $this->model->orderBy('create_date', 'desc')->orderBy('an_trua', 'desc');
        if($create_date != null || $create_date != ''){
            $query->where('create_date', $create_date);
        }
        $menu = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        if($menu) {
            foreach($menu as $dishes){
                $dishes->mon_chinh = @json_decode($dishes->mon_chinh);
                $dishes->mon_phu = @json_decode($dishes->mon_phu);
                $dishes->mon_rau = @json_decode($dishes->mon_rau);
                $food_ids = array_merge($dishes->mon_chinh, $dishes->mon_phu, $dishes->mon_rau);
                $foods = (new FoodRepository())->getFoodsByIds($food_ids)->groupBy('type');
                $dishes->foods = $foods;
            }
        }
        return $menu;
    }

}
