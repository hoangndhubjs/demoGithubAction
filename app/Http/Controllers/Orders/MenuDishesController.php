<?php
namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Repositories\FoodMenuRepository;
use App\Models\MealOrder;
use App\Models\Food;
use App\Classes\PaginateConfig;
use App\Classes\Settings\SettingManager;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MenuDishesController extends Controller
{
    use DatatableResponseable;

    private $menu;
    public function __construct(FoodMenuRepository $menu)
    {
        $this->menu = $menu;
    }

    public function index() {
        $page_title = 'Thêm món mới';
        $can_set_menu = SettingManager::getOption('employees_can_set_menu_dishes');
        return view('pages.orders.menu_dishes', compact('page_title', 'can_set_menu'));
    }

    public function listMenuDishes(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $create_date = $request->create_date ? $create_date = Carbon::createFromFormat('d-m-Y', $request->create_date)->format('Y-m-d') : null;
        $dishis = $this->menu->getMenuDishes($paginateConfig, $create_date);
        return $this->makeDatatableResponse($dishis, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    protected function _getPeriod() {
        $lunch_confirmed_hour = config('constants.ENDING_OF_MORNING');
        return time() < strtotime($lunch_confirmed_hour) ? MealOrder::TYPE_LUNCH : MealOrder::TYPE_DINNER;
    }

    public function addMenuDishesForm() {
        return view('pages.orders.make_menu_dishes');
    }

    public function addMenuDishes(Request $request) {
        $rules = array(
            'main_dishes' => 'required',
            'side_dishes' => 'required',
            'vegetable_dishes' => 'required'
        );
        $messages = array(
            'required' => __('field_required')
        );
        $request->validate($rules, $messages);

		$mon_chinh = explode("\n", $request->main_dishes);
		$mon_phu = explode("\n", $request->side_dishes);
        $mon_rau = explode("\n", $request->vegetable_dishes);
        
        foreach ($mon_chinh as $monchinh) {
			if ($id_monan = $this->checkDish($monchinh, 1)) {
				$monan_chinh[] = $id_monan;
			}
		}
		foreach ($mon_phu as $monphu) {
			if ($id_monan = $this->checkDish($monphu, 2)) {
				$monan_phu[] = $id_monan;
			}
		}
		foreach ($mon_rau as $monrau) {
			if ($id_monan = $this->checkDish($monrau, 3)) {
				$monan_rau[] = $id_monan;
			}
        }

        //check duplicate dish
        if($this->checkDuplicate($monan_chinh, $monan_phu) == true 
            || $this->checkDuplicate($monan_phu, $monan_rau) == true 
            || $this->checkDuplicate($monan_chinh, $monan_rau) == true) {
            return $this->responseError(__("Món chính, món phụ, món rau không được trùng nhau"));
        }

		$data = array(
			'mon_chinh' => json_encode($monan_chinh),
			'mon_phu' => json_encode($monan_phu),
			'mon_rau' => json_encode($monan_rau),
            'an_trua' => $this->_getPeriod(),
            'status' => 0,
			'create_date' => date('Y-m-d'),
        );
        
        $result = $this->menu->create($data);
       
        return $this->responseSuccess($result);
    }

    private function checkDish($monan, $type)
	{
		if (!$monan) {
            return false;  
        } 
		$slug = Str::slug(trim($monan), '_');
		$query = null; //emptying in case

        $query = Food::where('slug', $slug)->first();
       
		if ($query == null) {
			$data = array(
				'title' => $monan,
				'type' => $type,
                'slug' => $slug,
                'status' => 0
            );
            $create = Food::create($data);
			return $create->id;
		} else {
            return $query->id;
        } 
    }
    
    public function deleteMenuDishes(Request $request) {
        if (!$id = $request->id) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->menu->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    public function checkDuplicate($monA, $monB)
    {
        foreach($monA as $value) { 
            foreach($monB as $value1) { 
                if($value === $value1) { 
                    return true;
                }
            } 
        }
    }
}
