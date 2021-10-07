<?php

namespace App\Http\Controllers\Admin\Payrolls;

use App\Classes\PaginateConfig;
use App\Exports\Payroll;
use App\Http\Controllers\Controller;
use App\Models\AdvanceSalaries;
use App\Models\Info_CC;
use App\Models\SalaryPaychecks;
use App\Models\SalaryPayslip;
use App\Repositories\CaculatorSalariesRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\SalaryPayslipsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\SalaryManagerRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CompanyRepository;
use App\Traits\DatatableResponseable;
use App\Models\Employee;
use App\Repositories\AdvanceSalariesRepository;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Exports\ExportExcelSalary;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Department;
use App\Exports\PayrollBank;
use App\Models\Designation;
use App\Repositories\BankAccountRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Validator;
use PHPViet\NumberToWords\Transformer;

class PayrollController extends Controller
{
    use DatatableResponseable;

    private $company;
    private $payslip_manager;
    private $employee;
    private $advanceSalaries;
    private $dashboard;
    private $bank;

    public function __construct(
        CompanyRepository $company,
        SalaryManagerRepository $payslip_manager,
        EmployeeRepository $employee,
        AdvanceSalariesRepository $advanceSalaries,
        DashboardRepository $dashboard,
        BankAccountRepository $bank
    )
    {
        $this->company = $company;
        $this->payslip_manager = $payslip_manager;
        $this->employee = $employee;
        $this->advanceSalaries = $advanceSalaries;
        $this->dashboard = $dashboard;
        $this->bank = $bank;
    }

    public function list()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $page_title = __('mangerSalary');
        $date_before = date("m", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . "-1 month"));
//       $salary = $this->payslip_manager->getMaxMinSalaryUser();
        $salary = $this->getMaxMinSalaryUser();

        //chart
//        $payrollByDepartment = $this->payslip_manager->chartSalary();
        $payrollByDepartment = $this->chartSalary();
//        dd($payrollByDepartment);
        //chart
        return view('admin.payroll.financial_overview', compact('page_title', 'date_before', 'salary', 'payrollByDepartment'));
    }

    public function payroll()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $page_title = __('left_payroll');
        $date = now()->startOfMonth()->subMonth()->format("Y-m");
        $dateSub = now()->startOfMonth()->subMonth()->format("m-Y");
        $date_before = now()->startOfMonth()->subMonth()->format("m");//date("m", strtotime('-1 month'));
        $all_companies = $this->company->get_all_companies();
        $allDeparment = Department::where('company_id', Auth::user()->company_id)->get();

//        $dates = Carbon::createFromFormat('m-Y', $date);
        $dates_query = $date;
        $check_exits = $this->payslip_manager->check_exits_data($dates_query);
        return view('admin.payroll.payroll', compact('page_title', 'all_companies', 'date_before', 'allDeparment', 'date', 'check_exits', 'dateSub'));
    }

    public function history_payroll()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $page_title = __('xin_payslip_history');
        $all_companies = $this->company->get_all_companies();
        $date_before = date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . "-1 month"));

        return view('admin.payroll.history_payroll', compact('page_title', 'all_companies', 'date_before'));
    }

    public function add_payslip(Request $request)
    {
        $add_pay_all = $this->payslip_manager->payroll_salary_all($request);
        if ($add_pay_all == false) {
            return $this->responseError('create_salary_fail');
        }
        return $add_pay_all;
    }

    public function updateStatusPayslip(Request $request)
    {
        $data = $this->payslip_manager->updatePayments($request);
        return json_encode($data);
    }

    public function advance_money()
    {
        $page_title = __('advance_money');
        $all_companies = $this->company->get_company_by_user();
        $date_before = date("m-Y", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . "-1 month"));
        if (Auth::user()->isAdmin()) {
            return view('admin.payroll.advance_money', compact('page_title', 'all_companies', 'date_before'));
        }
        return view('pages.payroll.advance_money', compact('page_title', 'all_companies', 'date_before'));
    }

    // Ajax-Rquest
    public function ListPayroll(Request $request)
    {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $orders = $this->payslip_manager->getListPayroll($paginateConfig, $request);
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function listEmployeeCompany(Request $request)
    {
        $company_id = $request->get('company_id');
        $getMemberCompany = $this->employee->getEmployeesByCompany($company_id);
        return response()->json($getMemberCompany);
    }

    public function listAdvance_money(Request $request)
    {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $getAdvance = $this->payslip_manager->advance_money($paginateConfig, $request);
        return $this->makeDatatableResponse($getAdvance, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    // update Status
    public function advancceMoneyRequest(Request $request)
    {
        // update multi
        $data = ['status' => $request->status];
        $advance_id = $request->id;
        $advance_multi_id = $request->multi_id;
        if ($advance_multi_id) {
            $response = $this->advanceSalaries->updateAdvance($request->multi_id, $data);
        } else {
            $response = $this->advanceSalaries->update($advance_id, $data);
        }
        // update one
        if ($response) {
            if ($advance_id) {
                return $this->responseSuccess($advance_id);
            }
            if ($advance_multi_id) {
                return $this->responseSuccess($advance_multi_id);
            }
        } else {
            return $this->responseError(__("update_error"));
        }
    }

    //Chuyển sang trang xem, tải và download PDF
    public function pdfAdvanceSalary(Request $request)
    {
        $id = $request->id;
        // dd(gettype($id));
        if (gettype($id) == "string") {
            $advance_id = explode(",", $id);
        } else {
            $advance_id = $id;
        }
        $i = 0;
        // dd($advance_id);
        foreach ($advance_id as $item) {
            $transformer = new Transformer();
            $datapdf = AdvanceSalaries::find($item);
            $word_money[$i] = $transformer->toCurrency((int)$datapdf->advance_amount);
            // dd($datapdf);
            $employee = Employee::where('user_id', $datapdf->employee_id)->get();
            $department_id = $employee[0]->department_id;
            $designation_id = $employee[0]->designation_id;
            $departmentpdf = Department::select('department_name')->where('department_id', $department_id)->get();
            $designationpdf = Designation::select('designation_name')->where('designation_id', $designation_id)->get();
            $department[$i] = $departmentpdf[0]->department_name;
            $designation[$i] = $designationpdf[0]->designation_name;
            $data[$i] = $datapdf;
            $i += 1;
            // dd($datapdf);
            $pdf_advance = 'advance-salary-pdf' . '.pdf';
            // dd($pdf_advance);
        }
        // dd($word_money);
        // dd($data);
        $html = mb_convert_encoding(\Illuminate\Support\Facades\View::make('admin.payroll.advance_pdf', compact('data', 'pdf_advance', 'department', 'designation', 'word_money')), 'HTML-ENTITIES', 'UTF-8');
        // dd($html);
        return PDF::loadHtml($html)->stream($pdf_advance);
        // $pdf = PDF::loadView('admin.payroll.advance_pdf', compact('datapdf'));
        // return $pdf->download('advanceSalary.pdf');
    }

    public function getBankAccount(Request $request)
    {
        $dates = Carbon::createFromFormat('m-Y', $request->month_year);
        $date = $dates->format('Y-m');

        if (!Auth::user()->isAdmin() && $request->employee_id == '') {
            $employee_id = Auth::id();
        } else {
            $employee_id = $request->employee_id;
        }
        return $this->bank->getBankAccountByUser($employee_id, $date);
    }

    public function createAdvanceForm(Request $request)
    {

        $id = $request->get('id', null);
        $type = $request->type ?? 'create';
        if ($type == 'detail') {
            $salaryPayslips = $this->payslip_manager->find($id);
        } else {
            $salaryPayslips = '';
        }
        $overtime = null;
        $all_companies = $this->company->get_all_companies();
        if (Auth::user()->isAdmin()) {
            return view('admin.payroll.create_form', compact('overtime', 'type', 'all_companies', 'salaryPayslips'));
        }
        return view('pages.payroll.create_form', compact('type', 'salaryPayslips'));
    }

    public function createRequest(Request $request)
    {
        if ($request->reason == null) {
            return $this->responseError(__("Phải nhập lý do mới được tạm ứng"));
        }
        $convert_number = preg_replace('/\./', '', trim($request->advance_money));
        if ($convert_number <= 0) {
            return $this->responseError(__("Khoản tạm ứng phải lớn hơn 0đ"));
        }
        if (Auth::user()->isAdmin()) {
            $employee_id = $request->employee_id;
        } elseif ($request->employee_id == '') {
            $employee_id = Auth::id();
        }
        $company_id = $request->company_id ?? Auth::user()->company->company_id;

        $dates = Carbon::createFromFormat("m-Y", $request->month_year);
        $date = $dates->format('Y-m');
        $file_upload = '';
        if ($file = $request->file_advance) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/advance', $fileName, 'public');
            $file_upload = '/storage/' . $filePath;
        }
//        $checkEmty = $this->advanceSalaries->exitsMonthAdvance($employee_id, $date);
//       if ($checkEmty == true){
//           return $this->responseError(__("Tháng ".$request->month_year." đã ứng lương"));
//       }
        $data = [
            'company_id' => $company_id,
            'employee_id' => $employee_id,
            'reason' => $request->reason,
            'status' => 0,
            'advance_amount' => $convert_number,
            'month_year' => $date,
            'one_time_deduct' => 0,
            'monthly_installment' => 0,
            'total_paid' => 0,
            'file_upload' => $file_upload
        ];
        $response = $this->advanceSalaries->create($data);
        if ($response) {
            return $this->responseSuccess(__('xin_theme_success'), 200);
        } else {
            return $this->responseError(__("update_error"));
        }
    }

    public function exportExcelSalary(Request $request)
    {
        $employe_id = $request->employee_id;
        $company_id = $request->company_id != 'null' ? $request->company_id : '';
        $date = Carbon::createFromFormat('m-Y', $request->date);


        $checkDataInMonth = $this->payslip_manager->checkDataInMonth($date->format('Y-m'));

        if (count($checkDataInMonth) == 0) {
            return $this->responseError(__("update_error"), 200);
        } else {
            $export = new ExportExcelSalary($company_id, $employe_id, $date->format('Y-m'));
            $title = 'Bảng lương tháng ' . $request->date;
            return Excel::download($export, $title . '.xlsx');
        }
        // dd($request->all(), $checkDataInMonth);
    }

    public function payroll_month(Request $request)
    {
        $department_id = $request->department_id;
        $company_id = $request->company_id;
        $status = $request->status;

        $date = Carbon::createFromFormat('m-Y', $request->date);
        $checkDataInMonth = $this->payslip_manager->checkDataInMonth($date->format('Y-m'));

        if (count($checkDataInMonth) == 0) {
            return $this->responseError(__("update_error"), 200);
        } else {
            $export = new PayrollBank($request);
            $title = 'Bảng lương tháng ' . $request->date;
            return Excel::download($export, $title . '.xlsx');
        }
        // dd($request->all(), $checkDataInMonth);
    }

    public function check_piu()
    {
        $last_month = date('Y-m', strtotime('-1 month'));
//        $payslip_in_month = $this->getPayslipOf(81, $date_post);
        $employee_id = Employee::find(81);
        $payslip = app()->make(CaculatorSalariesRepository::class)->getCalculatedPayslip($employee_id, $last_month);


        $getOneSalary = SalaryPayslip::with([
            'employeeSalary',
            'employeeCompany',
            'department',
            'designation',
            'bankAccount',
            'employee.office_shift'
        ])->find(204);
        $month_Year = Carbon::createFromFormat('Y-m', $getOneSalary->salary_month);
        $dates = $month_Year->format('m-Y');
        if ($getOneSalary->wages_type == 1) {
            $status_wages = 'Chính thức';
        } elseif ($getOneSalary->wages_type == 2) {
            $status_wages = 'Thử việc';
        } elseif ($getOneSalary->wages_type == 3) {
            $status_wages = 'Partime';
        } elseif ($getOneSalary->wages_type == 4) {
            $status_wages = 'Thực tập';
        } else {
            $status_wages = '--';
        }
        return view('admin.payroll.mail', compact('getOneSalary', 'dates', 'status_wages'));
//        dd($payslip);
    }

    public function deleteAdvanceMoney(Request $request)
    {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->advanceSalaries->delete($id)) {
            return $this->responseSuccess(__(""));
        }
        return $this->responseError(__("delete_fail"));
    }

    public function getMaxMinSalaryUser()
    {
        $date_before = date("Y-m", strtotime(date("d-m-Y", strtotime(date("d-m-Y"))) . "-1 month"));
        $salary_all = SalaryPaychecks::where('month', $date_before)->get();

        if (count($salary_all) == 0) {
            return array();
        }
        $basics = array();
        $field_find_max = 0;
        $field_find_min = 0;
        $total_salary = 0;
        $user_id_max = 0;
        $user_id_min = 0;

        foreach ($salary_all as $key => $basic_salary) {
            $salary = $basic_salary->basic_salary ? $basic_salary->basic_salary : 0;
            $basics[] = $salary;
            $total_salary += $salary;

            if ($salary <= $field_find_min) {
                $field_find_min = $salary;
                $user_id_min = $basic_salary->employee_id;
            }

            if ($salary > $field_find_max) {
                $field_find_max = $salary;
                $user_id_max = $basic_salary->employee_id;
            }
        }

        $field_find_avg = array_sum($basics) / count($basics);

        $max_min = [$user_id_max, $user_id_min];

        $info_array = [];

        foreach ($max_min as $info) {
            $user_info = Employee::with(['department', 'company', 'designation'])->where('employee_id', $info)->first();

            $info_array[] = array(
                'name' => $user_info->first_name . ' ' . $user_info->last_name,
                'departmen' => $user_info->department != null ? $user_info->department->department_name : '',
                'company' => $user_info->company->name,
                'position' => $user_info->designation != null ? $user_info->designation->designation_name : ''
            );
        }

        $getBySalary = array(
            'max_salary' => ['salary' => $field_find_max, 'info' => $info_array[0]],
            'min_salary' => ['salary' => $field_find_min, 'info' => $info_array[1]],
            'avg_salary' => floor($field_find_avg),
            'total_salary' => $total_salary,
        );

        return $getBySalary;
    }

    public function chartSalary(){
        $user_info = Auth::user();
        $company_id = $user_info->company_id;
        $department = Department::where('company_id', $company_id)->get();
        $last_month = date('Y-m', strtotime('-1 month'));
        $payroll = [];
        foreach ($department as $key => $value) {
            if ($value->department_id == 11) {
                continue;
            } else {
                $payroll['name'][] = $value->department_name;
                $payroll['last_month'][] = SalaryPaychecks::where('month', $last_month)
                    ->where('department_id', $value->department_id)
                    ->sum('basic_salary');
                $payroll['average'][] = SalaryPaychecks::where('month', $last_month)
                    ->where('department_id', $value->department_id)
                    ->avg('basic_salary');
            }
        }
        return $payroll;
    }

}
