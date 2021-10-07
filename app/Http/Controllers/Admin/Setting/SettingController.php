<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\FinanceBankcash;
use App\Repositories\CompanyInfoRepository;
use App\Repositories\EmailConfigurationRepository;
use App\Repositories\FileManagerSettingRepository;
use App\Repositories\SettingRepository;
use App\Repositories\ThemeSettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    private $setting;
    private $companyInfo;
    private $fileManagerSettings;
    private $emailConfiguration;
    private $themeSettings;
    public function __construct(
        SettingRepository $setting,
        CompanyInfoRepository $companyInfo,
        FileManagerSettingRepository $fileManagerSettings,
        EmailConfigurationRepository $emailConfiguration,
        ThemeSettingsRepository $themeSettings
    ){
        $this->setting = $setting;
        $this->companyInfo = $companyInfo;
        $this->fileManagerSettings = $fileManagerSettings;
        $this->emailConfiguration = $emailConfiguration;
        $this->themeSettings = $themeSettings;

        $setting = $this->setting->getSetting(self::ID);
        View::share('adminSetting', $setting);
    }
    const ID = 1;

    public function accountSystem() {
        $page_title = __('xin_system');
        $setting = $this->setting->getSetting(self::ID);
        $currency = Currency::all();
        $timezones = $this->setting->all_timezones();
        $languages = $this->setting->all_languages(self::ID);

        return view('admin.setting.account-system', compact('page_title', 'setting', 'currency', 'timezones', 'languages'));
    }

    public function updateAccountSystem(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'application_name' => 'required',
                'footer_text' => 'required',
            ],
            [
                'application_name.required' => __('xin_error_application_name_field'),
                'footer_text.required' => __('xin_error_footer_text'),
            ]);

        if ($validator->passes()) {
            $data = [
                'application_name' => $request->application_name,
                "default_currency_symbol" => $request->default_currency_symbol,
                "default_currency" => $request->default_currency_symbol,
                "show_currency" => $request->show_currency,
                "currency_position" => $request->currency_position,
                "date_format_xi" => $request->date_format,
                "footer_text" => $request->footer_text,
                "enable_page_rendered" => $request->enable_page_rendered ?? '',
                "enable_current_year" => $request->enable_current_year ?? '',
                "employee_login_id" => $request->employee_login_id,
                "system_timezone" => $request->system_timezone,
                "google_maps_api_key" => $request->google_maps_api_key,
                "is_ssl_available" => $request->is_ssl_available ?? '',
                "default_language" => $request->default_language,
                "statutory_fixed" => $request->statutory_fixed ?? '',
                "invoice_terms_condition" => $request->invoice_terms_condition,
                "estimate_terms_condition" => $request->estimate_terms_condition,
                "show_projects" => $request->show_projects,
                "show_tasks" => $request->show_tasks,
            ];
            $response = $this->setting->update(self::ID, $data);

            if ($response == true) {
                return $this->responseSuccess(__('xin_success_system_configuration_updated'));
            } else {
                return $this->responseError(__('xin_error_msg'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function accountGeneral() {
        $page_title = __('xin_general');
        $companyInfo = $this->companyInfo->getCompanySettingInfo(self::ID);
        $country = $this->companyInfo->get_countries();
        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-general', compact('page_title', 'companyInfo', 'country', 'setting'));
    }

    public function updateAccountGeneral(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'company_name' => 'required',
                'contact_person' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric',
            ],
            [
                'company_name.required' => __('xin_employee_error_company_name'),
                'contact_person.required' => __('xin_clcontact_person_field_error'),
                'email.required' => __('xin_error_cemail_field'),
                'email.email' => __('xin_employee_error_invalid_email'),
                'phone.required' => __('xin_employee_error_mobile_phone'),
            ]);

        if ($validator->passes()) {
            $data = [
                "company_name" => $request->company_name,
                "contact_person" => $request->contact_person,
                "email" => $request->email,
                "phone" => $request->phone,
                "address_1" => $request->address_1,
                "address_2" => $request->address_2,
                "city" => $request->city,
                "state" => $request->state,
                "zipcode" => $request->zipcode,
                "country" => $request->country,
            ];

            $response = $this->companyInfo->update(self::ID, $data);

            if ($response == true) {
                return $this->responseSuccess(__('xin_success_company_info_updated'));
            } else {
                return $this->responseError(__('xin_error_msg'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function accountRole() {
        $page_title = __('xin_employee_role');

        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-role', compact('page_title', 'setting'));
    }

    public function updateAccountRole(Request $request) {
        $data = [
            "employee_manage_own_contact" => $request->employee_manage_own_contact ?? '',
            "employee_manage_own_document" => $request->employee_manage_own_document ?? '',
            "employee_manage_own_bank_account" => $request->employee_manage_own_bank_account ?? '',
            "employee_manage_own_picture" => $request->employee_manage_own_picture ?? '',
            "employee_manage_own_qualification" => $request->employee_manage_own_qualification ?? '',
            "employee_manage_own_profile" => $request->employee_manage_own_profile ?? '',
            "employee_manage_own_work_experience" => $request->employee_manage_own_work_experience ?? '',
            "employee_manage_own_social" => $request->employee_manage_own_social ?? '',
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_role_config_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountPayroll() {
        $page_title = __('left_payroll');

        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-payroll', compact('page_title', 'setting'));
    }

    public function updateAccountPayroll(Request $request) {
        $data = [
            "payslip_password_format" => $request->payslip_password_format,
            "is_payslip_password_generate" => $request->is_payslip_password_generate ?? 0,
            "enable_saudi_gosi" => $request->enable_saudi_gosi,
            "is_half_monthly" => $request->is_half_monthly,
            "half_deduct_month" => $request->half_deduct_month,
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_payroll_config_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountRecruitment() {
        $page_title = __('left_recruitment');

        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-recruitment', compact('page_title', 'setting'));
    }

    public function updateAccountRecruitment(Request $request) {
        $format = implode(',', array_column(json_decode($request->job_application_format), 'value'));
        $data = [
            "enable_job_application_candidates" => $request->enable_job_application_candidates ?? 0,
            "job_application_format" => $format
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_payroll_config_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountPerformance() {
        $page_title = __('left_performance');
        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-performance', compact('page_title', 'setting'));
    }

    public function updateAccountPerformance (Request $request) {
        $format_technical = implode(',', array_column(json_decode($request->technical_competencies), 'value'));
        $format_organizational = implode(',', array_column(json_decode($request->organizational_competencies), 'value'));
        $data = [
            "technical_competencies" => $format_technical,
            "organizational_competencies" => $format_organizational,
            "performance_option" => $request->performance_option
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_performance_config_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountSystemLogos() {
        $page_title = __('xin_system_logos');
        $setting = $this->setting->getSetting(self::ID);
        $company_info = $this->companyInfo->getCompanySettingInfo(self::ID);

        return view('admin.setting.account-system_logos', compact('page_title', 'setting', 'company_info'));
    }

    public function updateAccountSystemLogos1(Request $request) {
        $data = [];
        $messenger = __('apporce_advance_success');
        if (isset($request->p_file)) {
            $file = $request->p_file;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/logo', 'logo_'.$fileName, 'public');
            $data['logo'] = 'logo_'.$fileName;
            $messenger = __('xin_success_system_logo_updated');
        } else if (isset($request->favicon)) {
            $file = $request->favicon;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/logo/favicon/', 'favicon_'.$fileName, 'public');
            $data['favicon'] = 'favicon_'.$fileName;
            $messenger = __('xin_success_system_logo_updated');
        } else if (isset($request->sign_in_logo)) {
            $file = $request->sign_in_logo;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/logo/signin/', 'signin_'.$fileName, 'public');
            $data['sign_in_logo'] = 'signin_'.$fileName;
            $messenger = __('xin_success_signin_page_logo_updated');
        } else if (isset($request->job_logo)) {
            $file = $request->job_logo;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/logo/job/', 'job_'.$fileName, 'public');
            $data['job_logo'] = 'job_'.$fileName;
            $messenger = __('xin_recruitment_logo_updated');
        } else if (isset($request->payroll_logo)) {
            $file = $request->payroll_logo;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/logo/payroll/', 'payroll_'.$fileName, 'public');
            $data['payroll_logo'] = 'payroll_'.$fileName;
            $messenger = __('xin_success_payroll_logo_updated');
        }

        if (isset($request->job_logo) || isset($request->payroll_logo)) {
            $response = $this->setting->update(self::ID, $data);
        }else {
            $response = $this->companyInfo->update(self::ID, $data);
        }

        if ($response == true) {
            return $this->responseSuccess($messenger);
        } else {
            return $this->responseError(__('xin_error_msg'));
        }

    }

    public function accountPaymentGateway() {
        $page_title = __('xin_acc_payment_gateway');
        $setting = $this->setting->getSetting(self::ID);
        $bank_cash = FinanceBankcash::all();

        return view('admin.setting.account-payment_gateway', compact('page_title', 'setting', 'bank_cash'));
    }

    public function updateAccountPaymentGateway(Request $request) {

        $data = [
            "paypal_email" => $request->paypal_email,
            "paypal_sandbox" => $request->paypal_sandbox,
            "paypal_active" => $request->paypal_active,
            "stripe_secret_key" => $request->stripe_secret_key,
            "stripe_publishable_key" => $request->stripe_publishable_key,
            "stripe_active" => $request->stripe_active,
            "online_payment_account" => $request->bank_cash_id
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_acc_payment_gateway_info_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }

    }

    public function accountEmail() {
        $page_title = __('xin_email_notifications');
        $setting = $this->setting->getSetting(self::ID);
        $email_config = $this->emailConfiguration->getEmailConfiguration(self::ID);

        return view('admin.setting.account-email', compact('page_title', 'setting', 'email_config'));
    }

    public function updateAccountEmail(Request $request){

        $dataSystem = [
            "enable_email_notification" => $request->enable_email_notification ?? 'no',

        ];

        $data = [
            "email_type" => $request->email_type,
            "smtp_host" => $request->smtp_host,
            "smtp_username" => $request->smtp_username,
            "smtp_password" => $request->smtp_password,
            "smtp_port" => $request->smtp_port,
            "smtp_secure" => $request->smtp_secure,
        ];
        $responseSystem = $this->setting->update(self::ID, $dataSystem);
        $response = $this->emailConfiguration->update(self::ID, $data);

        if ($response == true && $responseSystem == true) {
            return $this->responseSuccess(__('xin_success_email_notify_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountPageLayouts() {
        $page_title = __('xin_page_layouts');
        $setting = $this->setting->getSetting(self::ID);
        $theme_setting = $this->themeSettings->getThemeSettings(self::ID);

        return view('admin.setting.account-page_layouts', compact('page_title', 'setting', 'theme_setting'));
    }

    public function updateAccountPageLayouts(Request $request){
        $data = [
            "statistics_cards" => $request->statistics_cards,
            "dashboard_option" => $request->dashboard_option,
            "login_page_options" => $request->login_page_options,
            "dashboard_calendar" => $request->dashboard_calendar ?? 'false',
            "login_page_text" => $request->login_page_text,
        ];

        $response = $this->themeSettings->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_thm_page_layouts_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountNotificationPosition() {
        $page_title = __('xin_notification_position');
        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-notification_position', compact('page_title', 'setting'));
    }

    public function updateAccountNotificationPosition(Request $request) {
        $data = [
            "notification_position" => $request->notification_position,
            "notification_close_btn" => $request->notification_close_btn ?? 'false',
            "notification_bar" => $request->notification_bar ?? 'false',
        ];

        $response = $this->setting->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_notify_position_config_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }
    }

    public function accountFiles() {
        $page_title = __('xin_files_manager');
        $setting = $this->setting->getSetting(self::ID);
        $file_setting = $this->fileManagerSettings->getFileManagerSetting(self::ID);

        return view('admin.setting.account-files', compact('page_title', 'setting', 'file_setting'));
    }

    public function updateAccountFiles(Request $request) {
        $format = implode(',', array_column(json_decode($request->allowed_extensions), 'value'));

        $data = [
            "maximum_file_size" => $request->maximum_file_size,
            "allowed_extensions" => $format,
            "is_enable_all_files" => $request->is_enable_all_files ?? ''
        ];

        $response = $this->fileManagerSettings->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_success_file_settings_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }

    }

    public function accountOrgChart() {
        $page_title = __('xin_org_chart_lnk');
        $setting = $this->setting->getSetting(self::ID);
        $theme_setting = $this->themeSettings->getThemeSettings(self::ID);

        return view('admin.setting.account-org_chart', compact('page_title', 'setting', 'theme_setting'));
    }

    public function updateAccountOrgChart(Request $request){

        $data = [
            "org_chart_layout" => $request->org_chart_layout,
            "export_file_title" => $request->export_file_title,
            "export_orgchart" => $request->export_orgchart ?? 'false',
            "org_chart_zoom" => $request->org_chart_zoom ?? 'false',
            "org_chart_pan" => $request->org_chart_pan ?? 'false',
        ];

        $response = $this->themeSettings->update(self::ID, $data);

        if ($response == true) {
            return $this->responseSuccess(__('xin_org_chart_info_updated'));
        } else {
            return $this->responseError(__('xin_error_msg'));
        }

    }

    public function accountTopMenu() {
        $page_title = __('xin_manage_top_menu');
        $setting = $this->setting->getSetting(self::ID);

        return view('admin.setting.account-topmenu', compact('page_title', 'setting'));
    }

    public function updateAccountSystemLogos(Request $request) {
        $data = [];
        $messenger = __('apporce_advance_success');
        if (isset($request->p_file)) {
            $file = $request->p_file;
            $name = 'logo';
            $data = $this->uploadImage($file, $name);
            $messenger = __('xin_success_system_logo_updated');
        }
        if (isset($request->favicon)) {
            $file = $request->favicon;
            $name = 'favicon';
            $data = $this->uploadImage($file, $name);
            $messenger = __('xin_success_system_logo_updated');
        }
        if (isset($request->sign_in_logo)) {
            $file = $request->sign_in_logo;
            $name = 'sign_in_logo';
            $data = $this->uploadImage($file, $name);
            $messenger = __('xin_success_signin_page_logo_updated');
        }
        if (isset($request->job_logo)) {
            $file = $request->job_logo;
            $name = 'job_logo';
            $data = $this->uploadImage($file, $name);
            $messenger = __('xin_recruitment_logo_updated');
        }
        if (isset($request->payroll_logo)) {
            $file = $request->payroll_logo;
            $name = 'payroll_logo';
            $data = $this->uploadImage($file, $name);
            $messenger = __('xin_success_payroll_logo_updated');
        }

        if (isset($request->job_logo) || isset($request->payroll_logo)) {
            $response = $this->setting->update(self::ID, $data);
        }else {
            $response = $this->companyInfo->update(self::ID, $data);
        }

        if ($response == true) {
            return $this->responseSuccess($messenger);
        } else {
            return $this->responseError(__('xin_error_msg'));
        }

    }

    function uploadImage($file, $name) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/logo/'.$name.'/', $name.'_'.$fileName, 'public');
        $data[$name] = $name.'_'.$fileName;

        return $data;
    }

}
