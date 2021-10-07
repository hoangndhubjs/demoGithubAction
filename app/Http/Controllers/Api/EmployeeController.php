<?php

namespace App\Http\Controllers\Api;
use App\Model\Employee;
use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EmployeeController extends Controller
{

    private $employee;
    public function __construct(EmployeeRepository $employee)
    {
        $this->employee = $employee;
    }

    const COMPANY_ID = 1; // HQ Group

    public function employee(Request $request)
    {

        if ($request->business_id) {

            $employee = $this->employee->getUserByCompnayId($request->business_id);

            if ($employee) {
                $data = [];
                foreach ($employee as $value) {
                    $data[] = [
                        'user_id' => $value->user_id,
                        'employee_id' => $value->employee_id,
                        'full_name' => $value->last_name . ' ' . $value->first_name,
                        'status' => $value->is_active,
                        'first_name'=> $value->first_name,
                        'last_name'=>$value->last_name,
                        'status_new_user'=>$value->status_new_user,
                        'status_finger'=>$value->status_finger
                    ];
                }
                $response = [
                    'status' => '200',
                    'messenger' => 'Success',
                    'data' => $data
                ];
            } else {
                $response = [
                    'status' => '404',
                    'messenger' => 'Fail',
                    'data' => ''
                ];
            }

        } else {
            $response = [
                'status' => '400',
                'messenger' => 'Truyền sai tham số.',
                'data' => ''
            ];
        }


        return response()->json($response);
    }
    private function addNumberEmployeeId($employee_id)
    {
        $total_string_len_in_system = 5;
        $count_string = strlen($employee_id);
        $new_employee_id = "";
        for ($i = 0; $i < ($total_string_len_in_system - $count_string); $i++) {
            $new_employee_id .= "0";
        }
        return $new_employee_id . $employee_id;
    }
    public function employeeUpdate(Request $request){

        $data =$request->all();

        if (!$request['user_id']) {
            header("HTTP/1.0 404 Not Found");
            print_r('<pre>');
            print_r(date("Y-m-d H:i:s", time()) . ': false');
            print_r('</pre>');
            die();
        }

        $employee_id = $this->addNumberEmployeeId($data['user_id']);

        DB::table('employees')->where('employee_id',$employee_id)->update(['status_new_user'=>0]);

    }

}
