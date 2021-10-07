<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Repositories\CompanyRepository;
use App\Repositories\GroupPermissionRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    use DatatableResponseable;

    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        $page_title = __('permissions');
        $page_description = __('');
        return view('pages.permissions.list', compact('page_title', 'page_description'));
    }

    public function createForm(Request $request){
        $id = $request->get('id', null);
        $companies = (new CompanyRepository())->getListCompany();
        $groups = (new GroupPermissionRepository())->getListGroup();
        $names = [
            'create' => 'create',
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
            'export' => 'export',
            'import' => 'import',
        ];
        $permission = null;
        if($id){
            $type = 'updated';
            $permission = $this->permission->find($id);
        } else {
            $type = 'created';
        }
        return view('pages.permissions.form_modal', compact('permission', 'type', 'companies', 'groups','names'));
    }

    /**
     * Cập nhận và tạo mới permission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePermission(Request $request){

        $data = array(
            'company_id' => $request->company_id,
            'group_permission_id' => $request->group_id,
            'name' => $request->group_id.'.'.$request->name_permission,
        );

        if ($id = $request->get('id')) {
            if($this->permission->where($data)->where('id', '!=' ,$id)->first()){
                return $this->responseError(__('permission_exists'));
            } else {
                $permission = $this->permission->update($id, $data);
            }
        } else {
            if($this->permission->where($data)->first()){
                return $this->responseError(__('permission_exists'));
            } else {
                $permission = $this->permission->create($data);
            }
        }
        return $this->responseSuccess($permission);
    }

    public function listPermission(){
        $groups = (new GroupPermissionRepository())->getAll();
        $data = array();
        foreach ($groups as $k => $group){
           $data[$k]['id'] = 'group_'.$group->id;
           $data[$k]['text'] = $group->name;
           $permissions = $this->permission->where('group_permission_id', $group->id)->get();
           $children = array();
           if($permissions){
               foreach ($permissions as $key => $permission){
                   $children[$key]['id'] = $permission->id;
                   $children[$key]['text'] = $permission->name;
               }
           }
           $data[$k]['children'] = $children??'false';
        }
        //dd(json_encode(array_values($data)));
        /*dd($data);*/
        /*dd(json_encode(array_values($data)));*/

        /*$a = '[{
                    "id":3,
                    "icon": "fa fa-folder icon-lg text-warning",
                    "text": "Node 1581798695",
                    "children": false
                }, 
                {
                    "id":2,
                    "icon": "fa fa-folder icon-lg text-success",
                    "text": "Node 1581798695",
                    "children": false
                }, 
                {
                  "id":1,
                  "icon": "fa fa-folder icon-lg text-success",
                  "text":"Root node",
                  "children":[
                    {"id":4,"text":"Child node 1"},
                    {"id":5,"text":"Child node 2"}
                  ]
                }]';*/
        return json_encode(array_values($data));
    }

    public function listPermissionByCompany(Request $request){
        $groups = (new GroupPermissionRepository())->getAll();
        if(isset($request->company_id) && $request->company_id != ''){
            $groups = $groups->where('company_id', $request->company_id);
        }

        $rolePermissions = array();
        if(isset($request->id) && $request->id != ''){
            $rolePermissions = DB::table("role_has_permissions")
                ->where("role_has_permissions.role_id",$request->id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
        }

        $data = array();
        foreach ($groups as $k => $group){
            $data[$k]['id'] = 'group_'.$group->id;
            $data[$k]['text'] = $group->name;
            $permissions = $this->permission->where('group_permission_id', $group->id)->get();
            $children = array();
            if($permissions){
                foreach ($permissions as $key => $permission){
                    $children[$key]['id'] = $permission->id;
                    $children[$key]['text'] = $permission->name;
                    if(isset($request->role_access) && $request->role_access == 1){
                        $children[$key]['state'] = ["selected" => true];
                    }
                    if(in_array($permission->id, $rolePermissions)){
                        $children[$key]['state'] = ["selected" => true];
                    }
                }
            }
            $data[$k]['children'] = $children??'false';
        }
        return json_encode(array_values($data));
    }
}