<?php
namespace App\Http\Controllers\Admin\TimeSheet;

use App\Classes\Settings\SettingManager;
use App\Http\Controllers\Controller;
use App\Repositories\BusinessSettingRepository;
use App\Repositories\EmployeeRepository;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FullAttendanceController extends Controller
{
    private $employee;
    private $businessRepo;

    public function __construct(EmployeeRepository $employeeRepo, BusinessSettingRepository $businessSettingRepo)
    {
        $this->employee = $employeeRepo;
        $this->businessRepo = $businessSettingRepo;
        View::share('adminSetting', true);
    }

    public function index() {
        $fullAttendanceEmployeeIds = SettingManager::getOption('employees_full_attendance', []);
        $employees = $this->employee->getEmployeeByIds($fullAttendanceEmployeeIds);
        return view('pages.timesheet.full_attendance', compact('employees'));
    }

    public function update(Request $request) {
        $employeeIds = $request->get('full_attendances');
        $businessId = SettingManager::getOption('businessId');
        $this->businessRepo->setBusinessOption($businessId, 'employees_full_attendance', $employeeIds);
        return redirect()->route('admin.timesheet.full_attendance')->with('alert_successes', [__('xin_theme_success')]);
    }

    public function list_full_allowance(){
        $fullAllowanceEmployeeIds = SettingManager::getOption('employees_full_allowance', []);
        $employees = $this->employee->getEmployeeByIds($fullAllowanceEmployeeIds);
        return view('pages.timesheet.full_allowance', compact('employees'));
    }

    public function update_full_allowance(Request $request) {
        $employeeIds = $request->get('full_allowance');
        $businessId = SettingManager::getOption('businessId');
        $this->businessRepo->setBusinessOption($businessId, 'employees_full_allowance', $employeeIds);
        return redirect()->route('admin.timesheet.full_allowance')->with('alert_successes', [__('xin_theme_success')]);
    }
}
