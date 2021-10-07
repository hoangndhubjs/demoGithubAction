<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Repositories\DepartmentRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    use DatatableResponseable;
    private $department;

    public function __construct(DepartmentRepository $department)
    {
        $this->department = $department;
    }
    public function index()
    {
        // dd(123);
        $page_title = __('left_department');
        return view('admin/organization/department/department', compact('page_title'));
    }


    public function createFormAssetDepartment(Request $request){
        $companies = Company::all();
        $employees = Employee::all();
        $locations = OfficeLocation::all();

        $department_id = $request->department_id;
        $department = null;
        if($department_id){
            $type = 'updated';
            $department = $this->department->find($department_id)->load('companyy', 'employeee', 'locationn');
            // dd($location);
        }else{
            $type = 'created';
        }
        return view('admin.organization.department.modal.edit_modal', compact('companies', 'employees', 'locations', 'department', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatableLists(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        // dd($paginateConfig);
        $data = $this->department->listDepartment($paginateConfig, $request);
        // dd($data);
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function optionLocation(Request $request){
        $id = $request->company_id;
        $location = OfficeLocation::Where('company_id',$id)->get();
        // dd($location);
        return $this->responseSuccess($location);
    }

    public function optionLocationHead(Request $request){
        $lo_id = $request->location_id;
        $location = OfficeLocation::where('location_id', $lo_id)->get();
        // dd($location);
        $lo_head = $location[0]->location_head;
        // dd($lo_head);
        $user = Employee::where('user_id', $lo_head)->get();
        // dd($user);
        return $this->responseSuccess($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'department_name' => 'required',
                'company_id' => 'required',
                'location_id' => 'required',
                'employee_id' => 'required',
                'department_name' => ['required', Rule::unique('departments', 'department_name')->ignore($request->department_id,'department_id')],
            ],
            [
                'department_name.required' => __('xin_error_cat_name_field'),
                'department_name.unique' => __('xin_employee_department_name_already_exist'),
            ]);
            // Auth::user()->tên cột => lấy cột thông tin người login mình muốn lấy
            //Auth::id() => lấy id người đang đăng nhập
            if($validator->passes()){
                $addDepartment = new Department();
                $id = $request->department_id;
                if($id !== null){
                    $department = Department::find($id);
                    $department->fill($request->all());
                    $department['added_by'] = Auth::id();
                    $response = $department->update();
                }else{
                    $addDepartment->fill($request->all());
                    $addDepartment['added_by'] = Auth::id();
                    $response = $addDepartment->save();
                }
                if($response === true){
                    return $this->responseSuccess(__('xin_theme_success'));
                }return $this->responseError(__('Thất bại'));
            }
            return response()->json(['errorsForm' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($id = $request->department_id) {
            $department = Department::find($id);
                $department->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }

}
