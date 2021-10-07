<?php

namespace App\Http\Controllers\Admin\ManagerEmployee;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\ContractType;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SecurityLevel;
use App\Repositories\EmployeeContractRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ContractController extends Controller
{
    private $employeeContract;
    use DatatableResponseable;

    public function __construct(EmployeeContractRepository $employeeContract)
    {
        $this->employeeContract = $employeeContract;
        if (\Route::is('employee_managements.staff_profile_set')) {
            View::share('adminViewProfileUser', false);
        } else {
            View::share('adminViewProfileUser', true);
        }
    }


    public function contract()
    {
        $page_title = __('xin_e_details_contract');

        $info_user = Employee::where('user_id', request()->route('id'))->first();
        if ($info_user == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view("admin.employee.contract.list", compact('page_title', 'info_user'));
    }

    public function datatableContract(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $listImmigration = $this->employeeContract->getEmployeeContract($paginateConfig, $request->route('id'));

        return $this->makeDatatableResponse($listImmigration, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormContract(Request $request) {

        $contract_type = ContractType::all();
        $designation = Designation::all();
        $info_user = Employee::where('user_id', request()->route('id'))->first();
        $id = $request->get('id', null);
        $employee_contract = null;

        if($id){
            $type = 'updated';
            $employee_contract = $this->employeeContract->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.employee.contract.form_modal', compact('employee_contract', 'type', 'contract_type', 'info_user', 'designation'));
    }

    public function createContract(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'contract_type_id' => 'required',
                'title' => 'required',
                'designation_id' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
            ],
            [
                'contract_type_id.required' => __('xin_employee_error_contract_type'),
                'title.required' => __('xin_error_cat_name_field'),
                'designation_id.required' => __('error_designation_field'),
                'from_date.required' => __('xin_error_start_date'),
                'to_date.required' => __('xin_error_end_date'),
            ]);

        if ($validator->passes()) {
            $data = [
                "employee_id" => $request->route('id'),
                "contract_type_id" => $request->contract_type_id,
                "title" => $request->title,
                "designation_id" => $request->designation_id,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "description" => $request->description ?? '',
            ];

            if ($id = $request->get('id')) {
                $response = $this->employeeContract->update($id, $data);
            } else {
                $response = $this->employeeContract->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function deleteContract(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->employeeContract->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

}
