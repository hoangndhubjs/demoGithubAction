<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function __construct()
    {
        View::share('adminSetting', true);
    }

    public function index(){
        $page_title = __('Thiết lập các module');
        $setting = SystemSetting::select('module_recruitment','module_travel','module_files','module_language','module_orgchart','module_events','module_chat_box','is_active_sub_departments','module_payroll','module_performance','module_sso')->first();
        return view('admin.module.index', compact('page_title', 'setting'));
    }

    public function update(Request $request)
    {
        $data = [
            'module_recruitment' => $request->mrecruitment,
            'module_travel' => $request->mtravel,
            'module_files' => $request->mfiles,
            'module_language' => $request->mlanguage,
            'module_orgchart' => $request->morgchart,
            'module_events' => $request->mevents,
            'module_chat_box' => $request->chatbox,
            'is_active_sub_departments' => $request->is_sub_departments,
            'module_payroll' => $request->module_payroll,
            'module_performance' => $request->module_performance,
            'module_sso' => $request->module_sso
        ];
        
        $query = SystemSetting::first()->update($data);
        
        if ($query) {
            return $this->responseSuccess(__('xin_success_system_modules_updated'));
        } else {
            return $this->responseError(__('update_error'));
        }
    }
}
