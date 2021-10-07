<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Repositories\DesignationRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    use DatatableResponseable;
    private $designation;

    public function __construct(DesignationRepository $designation)
    {
        $this->designation = $designation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = __('left_designation');
        return view('admin/organization/designation/designation', compact('page_title'));
    }

    public function createFormAssetDesignation(Request $request){
        $companies = Company::all();
        $departments = Department::all();

        $designation_id = $request->designation_id;
        $designation = null;
        if($designation_id){
            $type = 'updated';
            $designation = $this->designation->find($designation_id)->load('companyAsset', 'departmentAsset');
            // dd($designation);
        }else{
            $type = 'created';
        }
        return view('admin.organization.designation.modal.edit_modal', compact('companies', 'designation', 'departments', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatableLists(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        // dd($paginateConfig);
        $data = $this->designation->listDesignation($paginateConfig, $request);
        // dd($data);
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function optionDepartment(Request $request){
        $id = $request->company_id;
        $department = Department::Where('company_id',$id)->get();
        // dd($department);
        return $this->responseSuccess($department);
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
                'designation_name' => 'required',
                'company_id' => 'required',
                'department_id' => 'required',
                'description' => 'required',
            ],
            [
                'designation_name.required' => __('xin_error_cat_name_field'),
                'description.required' => __('xin_error_task_file_description'),
            ]);
            // Auth::user()->tên cột => lấy cột thông tin người login mình muốn lấy
            //Auth::id() => lấy id người đang đăng nhập
            if($validator->passes()){
                $addDesignation = new Designation();
                $id = $request->designation_id;
                if($id !== null){
                    $designation = Designation::find($id);
                    $designation->fill($request->all());
                    $designation['added_by'] = Auth::id();
                    $response = $designation->update();
                }else{
                    $addDesignation->fill($request->all());
                    $addDesignation['added_by'] = Auth::id();
                    $response = $addDesignation->save();
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
        if ($id = $request->designation_id) {
            $designation = Designation::find($id);
                $designation->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }

}
