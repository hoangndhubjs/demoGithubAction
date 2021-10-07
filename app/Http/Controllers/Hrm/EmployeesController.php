<?php

namespace App\Http\Controllers\Hrm;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\MoneyMinus;
use App\Repositories\AssetRepository;
use App\Repositories\CaculatorSalariesRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\MoneyMinusRepository;
use App\Repositories\SalaryPayslipsRepository;
use App\Rules\MatchOldPassword;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\DatatableResponseable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\SalaryAllowance;
use App\Repositories\AllowanceRepository;
use PDF;


use function GuzzleHttp\Promise\all;

class EmployeesController extends Controller
{
    private $employee;
    private $asset;
    private $salary_paslip;
    private $minus;
    private $allowance;
    use DatatableResponseable;

    public function __construct(
        EmployeeRepository $employee,
        AssetRepository $asset,
        SalaryPayslipsRepository $salary_paslip,
        MoneyMinusRepository $minus_repo,
        AllowanceRepository $allowance
    )
    {
        $this->employee = $employee;
        $this->asset = $asset;
        $this->salary_paslip = $salary_paslip;
        $this->minus = $minus_repo;
        $this->allowance = $allowance;
        View::share('isEmployeeModule', true);
    }

    // thong tin ca nhan
    public function profile()
    {
        $page_title = __('dashboard_personal_details');
        $page_description = ''; //Đây là trang thông tin nhân viên

        //info user
        $info_user = Auth::user();
        $gender = $info_user->gender;
        $marital_status = $info_user->marital_status;

        return view("employees.profile", compact('page_title', 'page_description', 'info_user', 'gender', 'marital_status'));
    }

    public function updateInfo(Request $request)
    {

        $user_id = Auth::id();
        $data = [
            'contact_no' => $request->contact_no,
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'address' => $request->address,
        ];

        if ($file = $request->profile_avatar) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/avatar', $fileName, 'public');
            $data['profile_picture'] = '/storage/' . $filePath;
        }
        if ($user_id) {
            $this->employee->update($user_id, $data);
            return response()->json(['success' => __('update_profile_success')]);
        } else {
            return response()->json(['error' => __('update_profile_error')]);
        }
    }

    public function work() {
        return view("employees.work");
    }

    // MANG XA HOI
    public function social()
    {
        $page_title = __('xin_e_details_social');

        //info user
        $info_user = Auth::user();

        return view("employees.account_social", compact('page_title', 'info_user'));
    }

    public function updateSocial(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'facebook_link' => 'nullable|url',
                'twitter_link' => 'nullable|url',
                'blogger_link' => 'nullable|url',
                'youtube_link' => 'nullable|url',
            ],
            [
//                'facebook_link.url' => 'Trường tên tài khoản không được để trống !',
//                'twitter_link.url' => 'Trường tên ngân hàng không được để trống !',
//                'blogger_link.url' => 'Trường số tài khoản không được để trống !',
//                'youtube_link.url' => 'Trường chi nhánh không được để trống !',
            ]);

        if ($validator->passes()) {

            $user_id = Auth::id();

            $data = [
                'facebook_link' => $request->facebook_link,
                'twitter_link' => $request->twitter_link,
                'blogger_link' => $request->blogger_link,
                'youtube_link' => $request->youtube_link
            ];

            $response = $this->employee->update($user_id, $data);
            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }

        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }

    // Tai san duoc giao
    public function assetAssign()
    {
        $page_title = __('xin_asset_assign');
        //info user
        $info_user = Auth::user();
        $gender = $info_user->gender;
        $marital_status = $info_user->marital_status;
        return view("employees.account_asset_assign", compact('page_title', 'info_user', 'gender'));
    }

    public function listAsset(Request $request)
    {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $user_info = Auth::user();
        $getAsset = $this->asset->getAseetUser($paginateConfig, $user_info);

        return $this->makeDatatableResponse($getAsset, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function changePassword()
    {
        $page_title = __('dashboard_personal_details');

        //info user
        $info_user = Auth::user();

        return view("employees.change-password", compact('page_title', 'info_user'));
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'current_password' => ['required', new MatchOldPassword()],
                'new_password' => 'required|min:6',
                'new_confirm_password' => 'required|min:6|same:new_password',
            ],
            [
                'current_password.required' => __('xin_employee_current_password'),
                'new_password.required' => __('xin_employee_new_password'),
                'new_password.min' => __('xin_employee_error_password_least'),
                'new_confirm_password.min' => __('xin_employee_error_password_least'),
                'new_confirm_password.required' => __('xin_employee_confirm_password'),
                'new_confirm_password.same' => __('xin_employee_confirm_password_err'),
            ]);
        if ($validator->passes()) {

            $response = $this->employee->update(Auth::id(), ['password' => bcrypt($request->new_password)]);

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    // bảng lương user
    public function salary_payslips()
    {
        $page_title = __('xin_payslip_history');
        return view('employees.salary.salary_payslips', compact('page_title'));
    }

    public function salary_payslips_list(Request $request)
    {
//        $all = SalaryPayslip::where('employee_id', Auth::id())->get();
//        $salary_user_all = response()->json($all);
//        return $salary_user_all;

        $paginateConfig = PaginateConfig::fromDatatable($request);
        $getSalaryUser = $this->salary_paslip->getPayslipUser($paginateConfig);

        return $this->makeDatatableResponse($getSalaryUser, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function detail_salary_user(Request $request)
    {
        $page_title = __('info_salary_user');
        $get_date = $request->input('month_filter');
        $date_before = now()->startOfMonth()->subMonth()->format("m-Y");
        $month = $get_date ?? $date_before;

        $user_id = Auth::id();
        if ($payroll = $request->payslip_id){
            $info = $this->salary_paslip->find($payroll);
            if ($info == null){
                return view('employees.salary.detail_salary', compact('info','page_title'));
            }
            $user_id = $info->employee_id;
            $month = $info->salary_month;

        }
        $salary_payslips = $this->salary_paslip->checkDataMonth($user_id, $month);
        $basicUser = [];

        if ($salary_payslips == null){
            return view('employees.salary.detail_salary', compact('salary_payslips','month','page_title'));
        }

        $basic_user_salary_tv =  $salary_payslips->trail_salary;
        $basic_user_salary_ct =  $salary_payslips->basic_salary;
        $total_day_in_month = $salary_payslips->total_working_days;
        $calulate_salary_trail = ((intval($basic_user_salary_tv) - 300000)) / $total_day_in_month;
        $calulate_salary_basic = ((intval($basic_user_salary_ct) - 300000)) / $total_day_in_month;

        $salary_trail = round($calulate_salary_trail * $salary_payslips->total_trial_working_days);
        $salary_basic = round($calulate_salary_basic * $salary_payslips->total_formal_working_days);


//        if ($salary_payslips && $salary_payslips->total_trial_working_days && $salary_payslips->total_formal_working_days != '0.0'){
            //Overtime
            $overtime_trail = ($salary_payslips->total_overtime_trial / 2) * $calulate_salary_trail;
            $overtime_formal = ($salary_payslips->total_overtime_formal / 2) * $calulate_salary_basic;

            $allowance_days = $salary_payslips->total_formal_working_days - ($salary_payslips->total_leave_half_days_formal/2) - ($salary_payslips->total_leave_full_days_formal);
            $salary_payslips->allowance_days = $allowance_days;

        $total_salary_holidays = ($salary_payslips->total_holidays_trail_work * $calulate_salary_trail) + (intval($salary_payslips->total_holidays) * $calulate_salary_basic);
        $amount_plus = $salary_trail + $salary_basic + $salary_payslips->total_allowances + $total_salary_holidays;
        $salary_payslips->amount_plus = $amount_plus;
            $basicUser = [
                'salary_trail'=>$salary_trail && $salary_trail > 0 ? $salary_trail : 0,
                'salary_basic'=>$salary_basic && $salary_basic > 0 ? $salary_basic : 0,
                'overtime_trail'=>$overtime_trail && $overtime_trail > 0 ? $overtime_trail : 0,
                'overtime_formal'=>$overtime_formal && $overtime_formal > 0 ? $overtime_formal : 0,
                'salary_one_day_trial' => $calulate_salary_trail && $calulate_salary_trail > 0 ? $calulate_salary_trail : 0,
                'salary_one_day_formal' => $calulate_salary_basic && $calulate_salary_basic > 0 ? $calulate_salary_basic :0,
                'total_salary_holidays' => $total_salary_holidays
            ];
//        dd($salary_payslips->total_holidays_trail_work.' '.$calulate_salary_trail, $salary_payslips->total_holidays.' '.$calulate_salary_basic);
        $total_day_leave_trail = $salary_payslips->total_leave_full_days_trial + $salary_payslips->total_leave_half_days_trial/2;
        $total_day_leave_formal = $salary_payslips->total_leave_full_days_formal + $salary_payslips->total_leave_half_days_formal/2;

        $total_leave = $total_day_in_month - $salary_payslips->total_all_work_month;
        if ($total_leave > 4) {
            $total_salary_leave = 0;
        } elseif ($total_leave >= 1) {
            $total_salary_leave = 200000;
        } else {
            $total_salary_leave = 0;
        }

        $data_leave_days = array(
            'total_day' =>  $total_leave,
            'total_salary_leave' => $total_salary_leave
        );
        $money_all = $this->minus->getMoneyMinus($salary_payslips->employee_id, $salary_payslips->salary_month);

        $get_allowances = SalaryAllowance::where('employee_id', $salary_payslips->employee_id)
            ->where('year_month', $salary_payslips->salary_month);
        $allowance_all = $get_allowances->get();
        $allowance_sum = $get_allowances->sum('allowance_amount');
        return view('employees.salary.detail_salary', compact('salary_payslips', 'page_title', 'month','basicUser','money_all','allowance_all','data_leave_days','allowance_sum'));
    }

    public function user_monthly_salary_slip(Request $request)
    {
        $page_title = __('info_salary_user');
        $get_date = $request->input('month_filter');
        // dd($get_date);
        $date_before = now()->startOfMonth()->subMonth()->format("m-Y");
        $month = $get_date ?? $date_before;

        $user_id = Auth::id();
        if ($payroll = $request->payslip_id){
            $info = $this->salary_paslip->find($payroll);
            $user_id = $info->employee_id;
            $month = $info->salary_month;
        }
        $salary_payslips = $this->salary_paslip->checkDataMonth($user_id, $month);
        $basicUser = [];
        return view('employees.salary.user_monthly_salary_slip', compact('salary_payslips', 'page_title', 'month','basicUser', 'get_date'));
    }

    public function create_form_pdf(Request $request){
        $user_id = Auth::id();
        $month = $request->month_filter;
        $payslip_id = $this->salary_paslip->detailSalaryUser($user_id, $month);
        if($payslip_id){
            $name_user = $payslip_id['employee_salary']['last_name'].' '.$payslip_id['employee_salary']['first_name'];
            $pdf_salary = 'Bảng lương tháng '. $month.' - '. $name_user.'.pdf';
            $html = mb_convert_encoding(\Illuminate\Support\Facades\View::make('employees.salary.pdf_salary',compact('payslip_id', 'pdf_salary')), 'HTML-ENTITIES', 'UTF-8');
            return PDF::loadHtml($html)->stream($pdf_salary);
        }else{
            $html = ' <p>Không tìm thấy dữ liệu lương tháng: <span>'.$month.'</span></p>';
            return PDF::loadHtml($html)->stream($html);
        }
    }

    /**
     * Dowload salary detail PDF
     */
    public function pdf_salary(Request $request){
        $user_id = Auth::id();
        $month = $request->month_filter;
        $payslip_id = $this->salary_paslip->detailSalaryUser($user_id, $month);
        if($payslip_id){
            $name_user = $payslip_id['employee_salary']['last_name'].' '.$payslip_id['employee_salary']['first_name'];
            $pdf_salary = 'Bảng lương tháng-'. $month.'-'. $name_user.'.pdf';
//            $html = mb_convert_encoding(\Illuminate\Support\Facades\View::make('employees.salary.pdf_salary',compact('payslip_id', 'pdf_salary')), 'HTML-ENTITIES', 'UTF-8');
            $html = PDF::loadView('employees.salary.pdf_salary', compact('payslip_id','pdf_salary'));
            return $html->download($pdf_salary);
        }
    }

    /**
     * lấy danh sách reports to
     *
     * @param Request $request
     * @return mixed
     */
    public function reportsTo(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $reportsTo = $this->employee->getReportsTo($company_id);
            } else {
                $reportsTo = $this->employee->getReportsTo($company_id, $term);
            }
            foreach ($reportsTo as $report) {
                $formatted_tags[] = ['id' => $report->user_id, 'text' => $report->last_name.' '.$report->first_name];
            }
        }

        return \Response::json($formatted_tags);
    }

    public function listEmployees(Request $request) {
        $withoutMe = $request->get('withoutMe', false);
        $withoutPaginate = $request->get("withoutPaginate", false);
        $ids = $request->get('ids', []);
        $search = $request->get("query");
        $cond = [];
        if ($withoutMe) {
            $cond['without'] = [Auth::id()];
        }
        if ($withoutPaginate) {
            $cond['withoutPaginate'] = true;
        }
        $cond['ids'] = $ids;
        $search && $cond['query'] = $search;
        $result = $this->employee->search($cond);
        if ($result instanceof Collection) {
            return [
                'success' => true,
                'data'    => $result
            ];
        }
        return $result;
    }
    /**
     * add data plus from detail salary (for Admin)
     */
    public function post_data_plus(Request $request){
        return $this->allowance->create_data_plus($request);
    }
}
