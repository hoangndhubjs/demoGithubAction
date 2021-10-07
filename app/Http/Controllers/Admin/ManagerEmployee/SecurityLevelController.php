<?php

namespace App\Http\Controllers\Admin\ManagerEmployee;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\SecurityLevel;
use App\Repositories\SecurityLevelRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SecurityLevelController extends Controller
{
    private $employeeSecurityLevel;
    use DatatableResponseable;

    public function __construct(SecurityLevelRepository $employeeSecurityLevel)
    {
        $this->employeeSecurityLevel = $employeeSecurityLevel;
        if (\Route::is('employee_managements.staff_profile_set')) {
            View::share('adminViewProfileUser', false);
        } else {
            View::share('adminViewProfileUser', true);
        }
    }


    public function securityLevel()
    {
        $page_title = __('xin_esecurity_level_title');

        $info_user = Employee::where('user_id', request()->route('id'))->first();
        if ($info_user == null) {
            return redirect()->route('employee_managements.staff_profile_set');
        }
        return view("admin.employee.security-level.list", compact('page_title', 'info_user'));
    }

    public function datatableSecurityLevel(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $listImmigration = $this->employeeSecurityLevel->getSecurityLevel($paginateConfig, $request->route('id'));

        return $this->makeDatatableResponse($listImmigration, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormSecurityLevel(Request $request) {

        $security_level = SecurityLevel::all();
        $id = $request->get('id', null);
        $employee_security_level = null;

        if($id){
            $type = 'updated';
            $employee_security_level = $this->employeeSecurityLevel->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.employee.security-level.form_modal', compact('employee_security_level', 'type', 'security_level'));
    }

    public function createSecurityLevel(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'security_type' => 'required',
            ],
            [
                'security_type.required' => __('xin_error_security_level_field'),
            ]);

        if ($validator->passes()) {

            $data = [
                "employee_id" => $request->route('id'),
                "security_type" => $request->security_type,
                "date_of_clearance" => $request->date_of_clearance ?? '',
                "expiry_date" => $request->expiry_date ?? '',
            ];

            if ($id = $request->get('id')) {
                $response = $this->employeeSecurityLevel->update($id, $data);
            } else {
                $response = $this->employeeSecurityLevel->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function deleteSecurityLevel(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->employeeSecurityLevel->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

}
