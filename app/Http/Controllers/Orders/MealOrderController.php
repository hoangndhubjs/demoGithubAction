<?php
namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Repositories\FoodMenuRepository;
use App\Repositories\MealOrderRepository;
use App\Classes\PaginateConfig;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use Illuminate\Support\Facades\Validator;
use App\Models\MealOrder;
use Illuminate\Support\Facades\Auth;

class MealOrderController extends Controller
{
    use DatatableResponseable;

    private $mealOrder;
    private $menu;
    public function __construct(MealOrderRepository $mealOrder, FoodMenuRepository $menu)
    {
        $this->mealOrder = $mealOrder;
        $this->menu = $menu;
    }

    public function index() {
        $page_title = 'Đặt cơm';
        $menu = $this->menu->getAvailableMenuToday();
        return view('pages.orders.meal_order', compact('page_title', 'menu'));
    }

    public function listMealOrders(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('create_date');
        }
        $orders = $this->mealOrder->getMyOrders($paginateConfig, $request->get('order_create_start_date'), $request->get('order_create_end_date'));
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createMealOrdersForm(Request $request) {
        $id = $request->get('id', null);
        $order = null;
        if (!$id) {
            $menu = $this->menu->getAvailableMenuToday();
        } else {
            $order = $this->mealOrder->getOrderById($id);
            $menu = $this->menu->getMenuOn($order->create_date, $order->type);
        }
        return view('pages.orders.make_meal_order', compact('menu', 'order'));
    }

    public function createMealOrders(Request $request) {
        $rules = array(
            'primary' => 'required|array|min:2',
            'secondary' => 'required',
            'tertiary' => 'required'
        );
        $messages = array(
            'min' => __('order_primary_must_at_least_2_options'),
            'required' => __('field_required'),
            'array' => __('field_wrong_format')
        );
        $request->validate($rules, $messages);
        $price = $this->_calculatePrice($request->primary);
        $data = array(
            'user_id' => Auth::id(),
            'mon_chinh' => @json_encode($request->primary),
            'mon_phu' => sprintf('["%s"]', $request->secondary),
            'mon_rau' => sprintf('["%s"]', $request->tertiary),
            'note' => $request->note ?? '',
            'type' => $this->_getPeriod(),
            'status' => MealOrder::STATUS_PENDING,
            'price' => $price,
            'create_date' => date("Y-m-d 00:00:00")
        );
        if ($id = $request->get('id')) {
            //remove un unpdate fields;
            unset($data['user_id'], $data['create_date'], $data['type']);
            $order = $this->mealOrder->update($id, $data);
        } else {
            $order = $this->mealOrder->create($data);
        }
        return $this->responseSuccess($order);
    }

    public function deleteMealOrder(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->mealOrder->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    protected function _calculatePrice($primary) {
        $barrierSelect = config('constants.MINIMUM_PRIMARY_MEAL_CAN_SELECT', 0);
        $price = config('constants.MINIMUM_MEAL_ORDER_PRICE', 0);
        $selected = count($primary);
        if ($selected > $barrierSelect) {
            $price += ($selected - $barrierSelect) * config('constants.OVER_PRICE_WHEN_SELECT_OVER_BARRIER', 0);
        }
        return $price;
    }

    protected function _getPeriod() {
        $lunch_confirmed_hour = config('constants.ENDING_OF_MORNING');
        return time() < strtotime($lunch_confirmed_hour) ? MealOrder::TYPE_LUNCH : MealOrder::TYPE_DINNER;
    }
}
