<?php

namespace App\Http\Controllers\Admin;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\GroupPermissionRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use DatatableResponseable;

    private $role;

    public function __construct(\App\Models\Role $role)
    {
        $this->role = $role;
    }

    public function index(Request $request)
    {
        $page_title = __('roles_list');
        $page_description = __('');
        return view('pages.roles.list', compact('page_title', 'page_description'));
    }

    public function listRole(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        /*$roles = $this->role->getComplaintByUserId($paginateConfig);*/
        $roles = $this->role->with('company')->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $this->makeDatatableResponse($roles, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
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
        $role = null;
        if($id){
            $type = 'updated';
            $role = $this->role->find($id);
        } else {
            $type = 'created';
        }
        return view('pages.roles.form_modal', compact('role', 'type', 'companies', 'groups','names'));
    }

    public function storeRole(Request $request){
        try {
            $this->validate($request, [
                'role_name' => "required|unique:roles,name,{$request->id},id",
                'company_id' => 'required',
                'role_access' => 'required',
            ]);

            if($permissions = $request->permissions){
                foreach ($permissions as $key => $permission){
                    if(strpos($permission, 'group') === false){} else{
                        unset($permissions[$key]);
                    }
                }
            }
            if($request->id){
                /*$role = $this->role->find($request->id);
                $role = $role->update(['name' => $request->role_name, 'company_id' => $request->company_id, 'role_access' => $request->role_access]);*/
                $role = Role::find($request->id);
                $role->name = $request->role_name;
                $role->company_id = $request->company_id;
                $role->role_access = $request->role_access;
                $role->save();
            } else {
                $role = Role::create(['name' => $request->role_name, 'company_id' => $request->company_id, 'role_access' => $request->role_access]);
            }

            $role->syncPermissions($permissions);

            return $this->responseSuccess(__('Thành công'));

        } catch (\Exception $e){
            dd($e->getMessage());
            return $this->responseError(__("Có lỗi xảy ra vui lòng thử lại"));
        }
    }
}