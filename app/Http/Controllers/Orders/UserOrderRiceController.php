<?php
namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Repositories\FoodMenuRepository;
use App\Repositories\MealOrderRepository;
use App\Classes\PaginateConfig;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Exports\ExportOrderRice;
use App\Models\MealOrder;
use Excel;

class UserOrderRiceController extends Controller
{
    use DatatableResponseable;

    private $mealOrder;
    private $menu;
    public function __construct(MealOrderRepository $mealOrder, FoodMenuRepository $menu)
    {
        $this->mealOrder = $mealOrder;
        $this->menu = $menu;
    }

    public function today() 
    {
        $page_title = 'Đặt cơm';
        $menu = $this->menu->getAvailableMenuToday();
        return view('pages.orders.user_order_rice', compact('page_title', 'menu'));
    }

    public function listOrderRiceToday(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $order_rice_today = $this->mealOrder->getAllOrdersToday($paginateConfig, $request->status, $request->type);
        return $this->makeDatatableResponse($order_rice_today, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function month() 
    {
        $page_title = 'Đặt cơm';
        return view('pages.orders.user_order_rice_month', compact('page_title'));
    }

    public function listOrderRiceMonth(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
    
        $order_rice_today_month = $this->mealOrder->getAllOrdersMonth($paginateConfig, $request->key_word, $request->month);
        
        return $this->makeDatatableResponse($order_rice_today_month, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    protected function _getPeriod() {
        $lunch_confirmed_hour = config('constants.ENDING_OF_MORNING');
        return time() < strtotime($lunch_confirmed_hour) ? MealOrder::TYPE_LUNCH : MealOrder::TYPE_DINNER;
    }

    public function exportOrderRice()
    {
        $type = $this->_getPeriod() == 1 ? "bua_trua_" : "bua_toi_";
        $today = date('d_m_Y');
        $output = "danh_sach_dat_com_".$type."ngay_".$today.".xlsx";
        return Excel::download(new ExportOrderRice, $output);
    }
}
