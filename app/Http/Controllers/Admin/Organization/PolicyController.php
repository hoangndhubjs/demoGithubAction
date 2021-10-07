<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyPolicy;
use App\Repositories\CompanyPolicyRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
{
    use DatatableResponseable;
    private $policy;

    public function __construct(CompanyPolicyRepository $policy)
    {
        $this->policy = $policy;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = __('left_policies');
        return view('admin/organization/policy/policy', compact('page_title'));
    }

    public function createFormAssetPolicy(Request $request){
        $companies = Company::all();

        $policy_id = $request->policy_id;
        $policy = null;
        if($policy_id){
            $type = 'updated';
            $policy = $this->policy->find($policy_id)->load('companyAsset');
            // dd($policy);
        }else{
            $type = 'created';
        }
        return view('admin.organization.policy.modal.edit_modal', compact('policy', 'companies', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatableLists(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        // dd($paginateConfig);
        $data = $this->policy->listCompanyPolicy($paginateConfig, $request);
        // dd($data);
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
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
                'title' => 'required',
                'description' => 'required',
            ],
            [
                'title.required' => __('xin_error_title'),
                'description.required' => __('xin_error_task_file_description'),
            ]);
            // Auth::user()->tên cột => lấy cột thông tin người login mình muốn lấy
            //Auth::id() => lấy id người đang đăng nhập
            if($validator->passes()){
                $addPolicy = new CompanyPolicy();
                $id = $request->policy_id;
                $has_file = $request->hasFile('attachment');
                $array = [
                    "company_id" => $request->company_id,
                    "title" => $request->title,
                    "description" => $request->description,
                ];
                if($id !== null){
                    $policy = CompanyPolicy::find($id);
                    if($has_file){
                        $policy->fill($request->all());
                        $get_img = $request->file('attachment');
                        $get_img_name = $get_img->getClientOriginalName();
                        $new_img = 'company_policy_' . uniqid() . '_' . str_replace('','_',$get_img_name);
                        $get_img->move('uploads/company_policy', $new_img);
                        $policy['attachment'] = $new_img;
                        $policy['added_by'] = Auth::id();
                    }else{
                        $policy->fill($array);
                        $policy['added_by'] = Auth::id();
                    }
                    $response = $policy->update();
                }else{
                    $addPolicy->fill($request->all());
                    $get_img = $request->file('attachment');
                    $get_img_name = $get_img->getClientOriginalName();
                    $new_img = 'company_policy_' . uniqid() . '_' . str_replace('','_',$get_img_name);
                    $get_img->move('uploads/company_policy', $new_img);
                    $addPolicy['attachment'] = $new_img;
                    $addPolicy['added_by'] = Auth::id();
                    $response = $addPolicy->save();
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
        if ($id = $request->policy_id) {
            $policy = CompanyPolicy::find($id);
                $policy->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }
}
