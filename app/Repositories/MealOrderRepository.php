<?php
namespace App\Repositories;

use App\Models\MealOrder;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use DB;
class MealOrderRepository extends Repository
{
    public function getModel(): string
    {
        return MealOrder::class;
    }

    public function getMyOrders($paginateConfig, $from = null, $to = null) {
        $user_id = Auth::id();
        $query = $this->model->with(['employee'])->where('user_id', $user_id);
        if($from) {
            $query->where('create_date', '>=', $from);
        }
        if ($to) {
            $query->where('create_date', '<=', $to);
        }
        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $orders = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        foreach($orders as $order) {
            $order->mon_chinh = @json_decode($order->mon_chinh);
            $order->mon_phu = @json_decode($order->mon_phu);
            $order->mon_rau = @json_decode($order->mon_rau);
            $food_ids = array_merge($order->mon_chinh, $order->mon_phu, $order->mon_rau);
            $foods = (new FoodRepository())->getFoodsByIds($food_ids)->keyBy('id');
            $order->foods = $foods;
        }
        return $orders;
    }

    public function getOrderById($id) {
        $order = $this->model->find($id);
        if ($order) {
            $order->mon_chinh = @json_decode($order->mon_chinh);
            $order->mon_phu = @json_decode($order->mon_phu);
            $order->mon_rau = @json_decode($order->mon_rau);
        }
        return $order;
    }

    public function getApprovedOrdersOf($employee_id, $date) {
        return $this->model->where([
            'user_id' => $employee_id,
            'create_date' => $date,
            'status' => MealOrder::STATUS_CONFIRMED
        ])->get();
    }
    public function caculater_datcom($user_id, $date_time){

        $list_datcom_only = $this->get_list_datcom_specified($user_id,$date_time);

        $list_haf_day = $this->get_leave_applications($user_id,$date_time); // lấy thời gian xin nghỉ
        $price_default = '25000';
        $array_order_leave = [];
        $array_leave_app = 0;
        $deduct = 0;$total_price_speci = 0;$receive_allowance=0;

        if ($list_haf_day){
            foreach ($list_haf_day as $leave_id){ //list ngay k tinh an trua

            }
            $array_order_leave[] = date("Y-m-d", strtotime($leave_id->applied_on));
        }
        $total_datcom_speci = $list_datcom_only == null ? 0 : count($list_datcom_only);

        if($list_datcom_only != null){
            foreach ($list_datcom_only as $datcom) {
                $date_datcom = date("Y-m-d", strtotime($datcom->create_date));
                if (in_array($date_datcom, $array_order_leave)) {
                    $array_leave_app += $datcom->price;
                } else {
                    $receive_allowance++;
                    if ($datcom->price > $price_default) {
                        $deduct_price = $datcom->price - $price_default;
                        $deduct += $deduct_price;
                    }
                }
                $total_price_speci += $datcom->price;
            }
        }
        unset($array_order_leave);
        $data = array(
            "price_datcom"=>$total_price_speci, // tổng số đặt cơm 1 tháng
            "total_day_datcom"=>$total_datcom_speci, // tổng số ngày đặt cơm
            "deduct" => $array_leave_app + $deduct, // tổng tiền đặt cơm   & số tiền cơm ngày làm đủ công > 25k
            "day_receive"=>$receive_allowance,
            "user_id"=>$user_id
        );
        return $data;
    }
    // get the datcom list for the specified month only (user_id)
    protected function get_list_datcom_specified($user_id, $date){
        $query = $this->model->where('user_id', $user_id)->where('status', 1)->where('create_date', 'like','' . $date.'%')->get();

      return count($query) > 0 ? $query : null;
    }
    protected function get_leave_applications($user_id, $date_time){
        $query = Leave::where('employee_id', $user_id)
                ->where('status', 2)
                ->where('applied_on','like','%'.$date_time.'%')
                ->get();
        return count($query) > 0 ? $query : null;
    }

    public function getAllOrdersToday($paginateConfig, $status = null, $type = null) {
        $query = $this->model->with(['employee'])->where('create_date', date('Y-m-d'));
        if($status != null || $status != ''){
            $query->where('status', $status);
        }
        if($type != null || $type != ''){
            $query->where('type', $type);
        }
        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $order_today = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        foreach($order_today as $order) {
            $order->mon_chinh = @json_decode($order->mon_chinh);
            $order->mon_phu = @json_decode($order->mon_phu);
            $order->mon_rau = @json_decode($order->mon_rau);
            $food_ids = array_merge($order->mon_chinh, $order->mon_phu, $order->mon_rau);
            $foods = (new FoodRepository())->getFoodsByIds($food_ids)->keyBy('id');
            $order->foods = $foods;
        }
        return $order_today;
    }

    public function getAllOrdersMonth($paginateConfig, $key_word = null, $month = null) {
        if($month != null || $month != ''){
            $startMonth = date('Y-'.$month).'-01';
            $endMonth = date('Y-'.$month.'-t');
        } else {
            $startMonth = date('Y-m').'-01';
            $endMonth = date("Y-m-t");
        }
        $query = DB::table('datcom_employee_order')
                    ->select('employees.first_name','employees.last_name','employees.employee_id', DB::raw('COUNT(id) as total_order_rice'), DB::raw('SUM(price) as total_price'))
                    ->where('status', 1)
                    ->whereBetween('create_date', [$startMonth, $endMonth])
                    ->join('employees', 'datcom_employee_order.user_id', '=', 'employees.user_id')
                    ->groupBy('datcom_employee_order.user_id');
        if($key_word != null || $key_word != ''){
            $query->where(function ($subQuery) use($key_word) {
                $subQuery->whereRaw("concat(first_name, ' ', last_name) like '%$key_word%' ")
                    ->orWhereRaw("concat(last_name, ' ', first_name) like '%$key_word%' ")
                    ->orWhereRaw("employee_id like '%$key_word%' ");
            });
        }
        $order_month = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $order_month;
    }
}
