<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@login');
Route::get('/logout', 'LoginController@logout')->name('logout');
Route::group(['middleware' => 'auth'], function () {
    //Test or another
    Route::get('test', 'Test@index')->name('test');
    Route::get('/quick-search', function () {
    })->name('quick-search');
    //For running
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/event', 'DashboardController@event')->name('event');
    Route::get('/quote', 'DashboardController@quote')->name('quote');
    Route::get('/import', 'DashboardController@import')->name('import');
    Route::get('/attendanceClasses', 'DashboardController@attendanceClasses')->name('attendanceClasses');
    Route::group(['prefix' => "employees"], function () {
        Route::get('profile', 'Hrm\EmployeesController@profile')->name('profile');
        Route::post('profile/update', 'Hrm\EmployeesController@updateInfo')->name('update_info');

        Route::get('work', 'Hrm\EmployeesController@work')->name('work');

        Route::get('immigration', 'Employees\ImmigrationController@immigration')->name('immigration');
        Route::get('immigration/list', "Employees\ImmigrationController@datatableImmigration")->name('immigrationList');
        Route::get('ajax/create-form-immigration', "Employees\ImmigrationController@createFormImmigration")->name('ajax.create_form_immigration');
        Route::post('immigration/create-immigration', 'Employees\ImmigrationController@createImmigration')->name('ajax.create_immigration');
        Route::delete('immigration/delete', 'Employees\ImmigrationController@delete_immigration')->name('deleteImmigration');

        Route::get('contact', 'Employees\ContactController@contact')->name('contact');
        Route::get('contact/list', "Employees\ContactController@datatableContact")->name('contactList');
        Route::get('ajax/create-form-contact', "Employees\ContactController@createFormContact")->name('ajax.create_form_contact');
        Route::post('contact/create-contact', 'Employees\ContactController@createContact')->name('ajax.create_contact');
        Route::delete('contact/delete', 'Employees\ContactController@delete_contact')->name('deleteContact');

        Route::get('social', 'Hrm\EmployeesController@social')->name('social');
        Route::post('social/update', 'Hrm\EmployeesController@updateSocial')->name('update_social');

        Route::get('qualification', 'Employees\QualificationController@qualification')->name('qualification');
        Route::get('qualification/list', "Employees\QualificationController@datatableQualification")->name('qualificationList');
        Route::get('ajax/create-form-qualification', "Employees\QualificationController@createFormQualification")->name('ajax.create_form_qualification');
        Route::post('qualification/create-qualification', 'Employees\QualificationController@createQualification')->name('ajax.create_qualification');
        Route::delete('qualification/delete', 'Employees\QualificationController@delete_qualification')->name('ajax.delete_qualification');

        Route::get('experience', 'Employees\ExperienceController@experience')->name('experience');
        Route::get('experience/list', "Employees\ExperienceController@datatableExperience")->name('ExperienceList');
        Route::get('ajax/create-form-experience', "Employees\ExperienceController@createFormExperience")->name('ajax.create_form_experience');
        Route::post('experience/create-experience', 'Employees\ExperienceController@createExperience')->name('ajax.create_experience');
        Route::delete('experience/delete', 'Employees\ExperienceController@delete_experience')->name('deleteExperience');

        Route::get('bank-account', 'Employees\BankAccountController@baccount')->name('baccount');
        Route::get('bank-account/list', "Employees\BankAccountController@datatableBankAccount")->name('baccountList');
        Route::get('ajax/create-form-bank-account', "Employees\BankAccountController@createFormBankAccount")->name('ajax.create_form_bank_account');
        Route::post('ajax/create-bank-account', "Employees\BankAccountController@createBankAccount")->name('ajax.create_bank_account');
        Route::delete('bank-account/delete', 'Employees\BankAccountController@deleteBankAccount')->name('deleteBankAccount');

        Route::get('asset_assign', 'Hrm\EmployeesController@assetAssign')->name('asset_assign');
        Route::get('asset/list', "Hrm\EmployeesController@listAsset")->name('asset_list');

        Route::get('password', "Hrm\EmployeesController@changePassword")->name('changePassword');
        Route::post('password', "Hrm\EmployeesController@updatePassword")->name('updatePassword');
        // salary payslips
        Route::get('salary_payslips', "Hrm\EmployeesController@salary_payslips")->name('salary_payslips');
        Route::get('salary_payslips/list', "Hrm\EmployeesController@salary_payslips_list")->name('salary_payslips_list');
        Route::match(['get', 'post'],'detail_salary_user', "Hrm\EmployeesController@detail_salary_user")->name('detail_salary_user');//ajax.form_detail
        Route::match(['get', 'post'],'user_monthly_salary_slip', "Hrm\SalaryPaycheckController@user_monthly_salary_slip")->name('user_monthly_salary_slip');//ajax.form_detail
//        Route::post('month_salary', "Hrm\EmployeesController@month_salary")->name('month_salary');//ajax.form_detail
        //router add plus or minus detail salary
        Route::post('post_data_plus', "Hrm\EmployeesController@post_data_plus")->name('post_data_plus');
        // salary_detail_user
        Route::get('create_form_pdf', "Hrm\EmployeesController@create_form_pdf")->name('create_form_pdf');
        Route::get('pdf_salary', "Hrm\EmployeesController@pdf_salary")->name('pdf_salary');
        Route::get('ajax/reports-to', "Hrm\EmployeesController@reportsTo")->name('ajax.reports_to');
        Route::get('ajax/list-active-employees', "Hrm\EmployeesController@listEmployees")->name('employees.ajax.list_employees');
    });

    // Route::resource('employees','Hrm\EmployeesController')->name('dashboard');
    Route::group(['prefix' => "orders", 'as' => 'orders.'], function () {
        Route::get('meal-order', "Orders\MealOrderController@index")->name('meal-order');
        Route::get('meal-order/create', "Orders\MealOrderController@create")->name('create-meal-order');
        Route::get('ajax/meal-orders', "Orders\MealOrderController@listMealOrders")->name('ajax.meal_orders');
        Route::get('ajax/create-meal-order', "Orders\MealOrderController@createMealOrdersForm")->name('ajax.create_meal_order');
        Route::post('ajax/create-meal-order', "Orders\MealOrderController@createMealOrders");
        Route::delete('ajax/delete-meal-order', 'Orders\MealOrderController@deleteMealOrder')->name('ajax.delete_meal_order');

        /*for admin*/
        //make new dishes
        Route::group(['middleware' => 'checkAdmin'], function () {
            Route::get('menu-dishes', "Orders\MenuDishesController@index")->name('menu-dishes');
            Route::get('ajax/menu-dishes', "Orders\MenuDishesController@listMenuDishes")->name('ajax.menu_dishes');
            Route::get('ajax/add-menu-dishes', 'Orders\MenuDishesController@addMenuDishesForm')->name('ajax.add_menu_dishes');
            Route::post('ajax/add-menu-dishes', 'Orders\MenuDishesController@addMenuDishes');
            Route::delete('ajax/delete-menu-dishes', 'Orders\MenuDishesController@deleteMenuDishes')->name('ajax.delete_menu_dishes');
            //user order rice
            Route::get('user-order-rice', "Orders\UserOrderRiceController@today")->name('user-order-rice');
            Route::get('ajax/user-order-rice', "Orders\UserOrderRiceController@listOrderRiceToday")->name('ajax.user_order_rice');
            //user order rice month
            Route::get('user-order-rice-month', "Orders\UserOrderRiceController@month")->name('user-order-rice-month');
            Route::get('ajax/user-order-rice-month', "Orders\UserOrderRiceController@listOrderRiceMonth")->name('ajax.user_order_rice_month');
            // export order
            Route::get('export-order-rice', "Orders\UserOrderRiceController@exportOrderRice")->name('export-order-rice');
        });

    });
    Route::group(['prefix' => "leaves", 'as' => 'leaves.'], function () {
        Route::get('list', "Leaves\LeavesController@index")->name('list');
        Route::get('detail/{id}', "Leaves\LeavesController@detail")->name('detail');
        Route::match(['get', 'post'], 'ajax/lists', "Leaves\LeavesController@listLeaves")->name('ajax.lists');
        Route::post('ajax/create-leave', "Leaves\LeavesController@store")->name('ajax.create_leave');
        Route::delete('ajax/delete', "Leaves\LeavesController@delete")->name('ajax.delete');
        // admin
        Route::get('admin/list', "Leaves\LeavesController@adminList")->name('admin.list');
        Route::get('admin/ajax/lists', "Leaves\LeavesController@adminAjaxList")->name('admin.ajax.list');
        Route::post('admin/ajax/confirm', "Leaves\LeavesController@adminConfirm")->name('admin.ajax.confirm');
        Route::get('ajax/department', "Leaves\LeavesController@listDepartment")->name('ajax.department');

        Route::get('leader/ajax/lists', "Leaves\LeavesController@leaderAjaxList")->name('leader.ajax.list');
        Route::post('leader/ajax/confirm', "Leaves\LeavesController@leaderApprove")->name('leader.ajax.approve');
        Route::post('leader/ajax/is_salary', "Leaves\LeavesController@updateIsSalary")->name('leader.ajax.is_salary');
    });
    Route::group(['prefix' => "calendars", 'as' => 'calendars.'], function () {
        Route::get('list', "Calendars\CalendarsController@index")->name('list');
        Route::get('calendar_post', "Calendars\CalendarsController@calendar_post")->name('calendar_post');
        Route::get('get_employee_company', "Calendars\CalendarsController@get_employee_company")->name('get_employee_company');
        Route::post('add_mettings', "Calendars\CalendarsController@add_mettings")->name('add_mettings');
        Route::post('detail_calendar', "Calendars\CalendarsController@detail_calendar")->name('detail_calendar');
        Route::post('detail_leave', "Calendars\CalendarsController@detail_leave")->name('detail_leave');

    });
    Route::group(['prefix' => "office-shift", 'as' => 'office-shift.'], function () {
        Route::get('list', "OfficeShift\OfficeShiftController@index")->name('list');
        Route::get('ajax/office-shift', "OfficeShift\OfficeShiftController@listOfficeShift")->name('ajax.lists');
        Route::get('ajax/add-office-shift', "OfficeShift\OfficeShiftController@addOfficeShiftForm")->name('ajax.add_office_shift');
        Route::post('ajax/add-office-shift', "OfficeShift\OfficeShiftController@addOfficeShift");
        Route::delete('ajax/delete-office-shift', 'OfficeShift\OfficeShiftController@deleteOfficeShift')->name('ajax.delete_office_shift');
        Route::post('ajax/update-default-shift', 'OfficeShift\OfficeShiftController@updateDefaultShift')->name('ajax.update_default_shift');
        Route::get('ajax/office-shift-by-company', "OfficeShift\OfficeShiftController@listOfficeShiftByCompany")->name('ajax.office_shift_by_company');
    });
    Route::group(['prefix' => "timesheet", 'as' => 'timesheet.'], function () {
        Route::get('attendance', 'TimeSheet\TimeSheetController@attendance')->name('attendance');
        Route::get('attendance-monthly', 'TimeSheet\TimeSheetController@attendance_monthly')->name('attendance_monthly');
        Route::get('attendance-list', 'TimeSheet\TimeSheetController@listAttendance')->name('ajax.attendance.list');
        Route::get('attendance-monthly-list', 'TimeSheet\TimeSheetController@listAttendanceMonthly')->name('ajax.attendance_monthly.list');
        Route::get("attendance-monthly-summary", 'TimeSheet\TimeSheetController@attendanceMonthlySummary')->name('ajax.attendance_monthly.summary');
    });

    Route::namespace ('Admin\Organization')->group(function () {
        Route::group(['prefix' => "company", 'as' => 'company.'], function () {
            Route::get('/', 'CompanyController@index')->name('index');
            Route::get('ajax-company', 'CompanyController@datatableLists')->name('ajax.lists');
            Route::get('ajax/create-form-asset-company', "CompanyController@createFormAssetCompany")->name('create_form_asset_company');
            Route::post('update', 'CompanyController@update')->name('update');
            Route::delete('delete-company', 'CompanyController@destroy')->name('destroy');
        });
        Route::group(['prefix' => "location", 'as' => 'location.'], function () {
            Route::get('/', 'LocationController@index')->name('index');
            Route::get('ajax-location', 'LocationController@datatableLists')->name('ajax.lists');
            Route::get('ajax/create-form-asset-location', "LocationController@createFormAssetLocation")->name('create_form_asset_location');
            Route::post('update', 'LocationController@update')->name('update');
            Route::delete('delete-location', 'LocationController@destroy')->name('destroy');
        });
        Route::group(['prefix' => "department", 'as' => 'department.'], function () {
            Route::get('/', 'DepartmentController@index')->name('index');
            Route::get('ajax-department', 'DepartmentController@datatableLists')->name('ajax.lists');
            Route::get('ajax-option-location', 'DepartmentController@optionLocation')->name('optionLocation');
            Route::get('ajax-option-location-head', 'DepartmentController@optionLocationHead')->name('optionLocationHead');
            Route::get('ajax/create-form-asset-department', "DepartmentController@createFormAssetDepartment")->name('create_form_asset_department');
            Route::post('update', 'DepartmentController@update')->name('update');
            Route::delete('delete-department', 'DepartmentController@destroy')->name('destroy');
        });
        Route::group(['prefix' => "designation", 'as' => 'designation.'], function () {
            Route::get('/', 'DesignationController@index')->name('index');
            Route::get('ajax-designation', 'DesignationController@datatableLists')->name('ajax.lists');
            Route::get('ajax-option-department', 'DesignationController@optionDepartment')->name('optionDepartment');
            Route::get('ajax/create-form-asset-designation', "DesignationController@createFormAssetDesignation")->name('create_form_asset_designation');
            Route::post('update', 'DesignationController@update')->name('update');
            Route::delete('delete-designation', 'DesignationController@destroy')->name('destroy');
        });
        Route::group(['prefix' => "announcement", 'as' => 'announcement.'], function () {
            Route::get('/', 'AnnouncementController@index')->name('index');
            Route::get('ajax-announcement', 'AnnouncementController@datatableLists')->name('ajax.lists');
            Route::get('ajax-option-location', 'AnnouncementController@optionLocation')->name('optionLocation');
            Route::get('ajax-option-department', 'AnnouncementController@optionDepartment')->name('optionDepartment');
            Route::get('ajax/create-form-asset-announcement', "AnnouncementController@createFormAssetAnnouncement")->name('create_form_asset_announcement');
            Route::post('update', 'AnnouncementController@update')->name('update');
            Route::delete('delete-announcement', 'AnnouncementController@destroy')->name('destroy');
        });
        Route::group(['prefix' => "policy", 'as' => 'policy.'], function () {
            Route::get('/', 'PolicyController@index')->name('index');
            Route::get('ajax-policy', 'PolicyController@datatableLists')->name('ajax.lists');
            Route::get('ajax/create-form-asset-policy', "PolicyController@createFormAssetPolicy")->name('create_form_asset_policy');
            Route::post('update', 'PolicyController@update')->name('update');
            Route::delete('delete-policy', 'PolicyController@destroy')->name('destroy');
        });
    });

    Route::group(['prefix' => "notification", 'as' => 'notification.'], function () {
        Route::get('list', "Notifications\NotificationsController@index")->name('list');
        /*Route::get('ajax/notification', "Notifications\NotificationsController@listOfficeShift")->name('ajax.lists');*/
        Route::get('ajax/lists', "Notifications\NotificationsController@listNotifi")->name('ajax.lists');
        Route::post('ajax/update', "Notifications\NotificationsController@updateNotifi")->name('ajax.update');
    });

    Route::group(['prefix' => "overtime-request", 'as' => 'overtime_request.'], function () {
        Route::get('list', "Overtime\OvertimeController@index")->name('list');
        Route::get('detail/{id}', "Overtime\OvertimeController@detail")->name('detail');
        Route::get('ajax/overtime-request', "Overtime\OvertimeController@listOvertime")->name('ajax.lists');
        Route::delete('ajax/delete-overtime', 'Overtime\OvertimeController@deleteOvertime')->name('ajax.delete_overtime');
        Route::get('ajax/create-form-overtime', "Overtime\OvertimeController@createOvertimeForm")->name('ajax.create_form');
        Route::post('ajax/update-request', "Overtime\OvertimeController@updateRequestOvertime")->name('ajax.update_request');

        Route::get('ajax/overtime-request-admin', "Overtime\OvertimeController@listOvertimeAdmin")->name('ajax.admin_lists');
        Route::post('ajax/overtime-request', "Overtime\OvertimeController@approveRequest")->name('ajax.approve');
    });
    Route::group(['prefix' => "complaint", 'as' => 'complaint.'], function () {
        Route::get('list', "Complaint\ComplaintController@index")->name('list');
        Route::get('ajax/complaint', "Complaint\ComplaintController@listComplaint")->name('ajax.lists');
        Route::delete('ajax/delete-overtime', 'Complaint\ComplaintController@deleteComplaint')->name('ajax.delete_complaint');
        Route::get('ajax/create-form-complaint', "Complaint\ComplaintController@createComplaintForm")->name('ajax.create_form');
        Route::post('ajax/update-complaint', "Complaint\ComplaintController@updateComplaint")->name('ajax.update_complaint');
    });
    //File Managers
    Route::group(['prefix'=>'files', 'as' => 'files.'], function () {
        Route::get('/', 'Files\FileController@index')->name("filemanager");
        Route::get('/files', 'Files\FileController@files')->name('ajax.list');
        Route::post('/upload', 'Files\FileController@upload')->name('ajax.upload');
        Route::get('/download', 'Files\FileController@download')->name('download');
        Route::delete('/delete', 'Files\FileController@delete')->name('ajax.delete');
        //Route::get('/test', 'Files\FileController@test');
    });
    //meeting
    Route::group(['prefix' => "meeting", 'as' => 'meeting.'], function () {
        Route::get('list', "Meeting\MeetingController@list")->name('list');
        Route::get('detail/{id}', "Meeting\MeetingController@detail")->name('detail');
    });
    //compensation
    Route::group(['prefix' => "compensations", 'as' => 'compensations.'], function () {
        Route::get('/', "Compensations\CompensationsController@list")->name('list');
        Route::get('ajax/compensation-request', "Compensations\CompensationsController@listCompensations")->name('ajax.lists');
        Route::get('ajax/create-form', "Compensations\CompensationsController@createCompensationForm")->name('ajax.create_form');
        Route::post('add_compensations', "Compensations\CompensationsController@add_compensations")->name('add_compensations');
        Route::delete('ajax/delete-compensations', 'Compensations\CompensationsController@deleteCompensations')->name('ajax.delete_compensations');
        Route::get('ajax/update-compensations', 'Compensations\CompensationsController@updateCompensations')->name('ajax.update_compensations');
        Route::get('compensations/{id}', "Compensations\CompensationsController@detail")->name('detail');
        Route::get('compensations_check', "Compensations\CompensationsController@compensationsCheck")->name('compensations_check');
        Route::get('setCompensation', "Compensations\CompensationsController@setApprovedCompensation")->name('setCompensation');
    });
    //Route::resource('permissions','Admin\PermissionsController');
    Route::group(['prefix' => "permission", 'as' => 'permission.'], function () {
        Route::get('list', "Admin\PermissionsController@index")->name('list');
        Route::get('ajax/create-form', "Admin\PermissionsController@createForm")->name('ajax.create_form');
        Route::post('ajax/store', "Admin\PermissionsController@storePermission")->name('ajax.store');
        Route::get('ajax/list', "Admin\PermissionsController@listPermission")->name('ajax.list');
        Route::post('ajax/list', "Admin\PermissionsController@listPermissionByCompany")->name('ajax.list');
    });
    Route::group(['prefix' => "role", 'as' => 'role.'], function () {
        Route::get('list', "Admin\RolesController@index")->name('list');
        Route::get('ajax/create-form', "Admin\RolesController@createForm")->name('ajax.create_form');
        Route::post('ajax/store', "Admin\RolesController@storeRole")->name('ajax.store');
        Route::get('ajax/list', "Admin\RolesController@listRole")->name('ajax.lists');
    });
//    Route::group(['prefix' => 'fileman', 'as' => 'fileman.'], function () {
//        \UniSharp\LaravelFilemanager\Lfm::routes();
//    });
    //---------------------Admin-------------------------
    Route::namespace('Admin\Payrolls')->group(function () {
        Route::group(['prefix'=> "payrolls", 'as' => 'payrolls.'], function (){
            Route::get('/','PayrollController@list')->name('list');
            Route::get('/payroll','PayrollController@payroll')->name('payroll');
            Route::get('/history_payroll','PayrollController@history_payroll')->name('history_payroll');
            Route::get('/advance_money','PayrollController@advance_money')->name('advance_money');
            //list_allowance
            Route::get('ajax/lists', "PayrollController@ListPayroll")->name('ajax.lists');
            Route::get('listEmployeeCompany', "PayrollController@listEmployeeCompany")->name('listEmployeeCompany');

            //advance_money
            Route::get('listAdvance_money', "PayrollController@listAdvance_money")->name('listAdvance_money');
            Route::get('advancceMoneyRequest', "PayrollController@advancceMoneyRequest")->name('advancceMoneyRequest');
            Route::get('advance_pdf', "PayrollController@pdfAdvanceSalary")->name('pdfAdvanceSalary');
            Route::get('ajax/create-form', "PayrollController@createAdvanceForm")->name('ajax.create_form');
            Route::post('createRequest', "PayrollController@createRequest")->name('createRequest');
            Route::get('getBankAccount', "PayrollController@getBankAccount")->name('getBankAccount');
            // detail_salary_payroll
//            Route::get('ajax/form_salary_detail', "PayrollController@createAdvanceForm")->name('ajax.form_salary_detail');
            Route::get('exportExcelSalary', "PayrollController@exportExcelSalary")->name('exportExcelSalary');
            Route::get('payroll_month', "PayrollController@payroll_month")->name('payroll_month');
            //add_payslip
            Route::get("add_payslip", "PayrollController@add_payslip")->name('add_payslip');
            Route::get("update_payslip", "PayrollController@updateStatusPayslip")->name('update_payslip');

            Route::get('test_salary', 'PayrollController@check_piu');

            //user
            Route::delete('payroll/delete-advance-money', 'PayrollController@deleteAdvanceMoney')->name('delete_advance_money');

            Route::get('timekeeping-payroll-data', 'PayrollGoogleSheetController@index')->name('timekeeping_payroll_data');
            Route::get('payroll-data', 'PayrollGoogleSheetController@payrollToSheet')->name('payroll-data');
        });


        Route::get('test', 'PayrollGoogleSheetController@getAllDataSheet')->name('test');
    });

    //-----------------Quản lý nhân viên--------------------------------
    Route::namespace('Admin\ManagerEmployee')->group(function () {
        Route::group(['prefix' => "employee_managements", 'as' => 'employee_managements.'], function () {
            Route::get('/', 'ManagerEmployeeController@list')->name('list');
            Route::get('staff_profile_set', 'ManagerEmployeeController@staff_profile_set')->name('staff_profile_set');
            Route::get('set_staff/{id}', 'ManagerEmployeeController@setStaff')->name('setStaff');
            Route::get('staff_allowance/{id}', 'ManagerEmployeeController@staff_allowance')->name('staff_allowance');
            Route::get('statutory_deductions/{id}', 'ManagerEmployeeController@statutory_deductions')->name('statutory_deductions');
            Route::get('other_payment/{id}', 'ManagerEmployeeController@other_payment')->name('other_payment');
            Route::get('loan_deductions/{id}', 'ManagerEmployeeController@loan_deductions')->name('loan_deductions');
            Route::get('fine/{id}', 'ManagerEmployeeController@fine')->name('fine');
            Route::get('money_minus/{id}', 'ManagerEmployeeController@money_minus')->name('money_minus');
            Route::get('money_minus/list/{id}', "ManagerEmployeeController@datatableMoneyMinus")->name('moneyMinusList');
            Route::get('ajax/create-form-money-minus/{id}', "ManagerEmployeeController@createFormMoneyMinus")->name('create_form_money_minus');
            Route::post('money_minus/create-money-minus', 'ManagerEmployeeController@createMoneyMinusUser')->name('create_money_minus');
            Route::delete('money_minus/delete', 'ManagerEmployeeController@deleteMoneyMinusUser')->name('deleteMoneyMinusUser');
            //add minus from detail_salry
            Route::post('post_data_minus', 'ManagerEmployeeController@post_data_minus')->name('post_data_minus');


            Route::get('immigration/{id}', 'ImmigrationController@immigration')->name('immigration');
            Route::get('immigration/list/{id}', "ImmigrationController@datatableImmigration")->name('immigrationList');
            Route::get('ajax/create-form-immigration/{id}', "ImmigrationController@createFormImmigration")->name('ajax.create_form_immigration');
            Route::post('immigration/create-immigration/{id}', 'ImmigrationController@createImmigration')->name('ajax.create_immigration');
            Route::delete('immigration/delete/', 'ImmigrationController@delete_immigration')->name('deleteImmigration');

            Route::get('contact/{id}', 'ContactController@contact')->name('contact');
            Route::get('contact/list/{id}', "ContactController@datatableContact")->name('contactList');
            Route::get('ajax/create-form-contact/{id}', "ContactController@createFormContact")->name('ajax.create_form_contact');
            Route::post('contact/create-contact/{id}', 'ContactController@createContact')->name('ajax.create_contact');
            Route::delete('contact/delete', 'ContactController@delete_contact')->name('deleteContact');

            Route::get('social/{id}', 'ManagerEmployeeController@social')->name('social');
            Route::post('social/update{id}', 'ManagerEmployeeController@updateSocial')->name('update_social');

            Route::get('qualification/{id}', 'QualificationController@qualification')->name('qualification');
            Route::get('qualification/list/{id}', "QualificationController@datatableQualification")->name('qualificationList');
            Route::get('ajax/create-form-qualification/{id}', "QualificationController@createFormQualification")->name('ajax.create_form_qualification');
            Route::post('qualification/create-qualification/{id}', 'QualificationController@createQualification')->name('ajax.create_qualification');
            Route::delete('qualification/delete', 'QualificationController@delete_qualification')->name('ajax.delete_qualification');

            Route::get('experience/{id}', 'ExperienceController@experience')->name('experience');
            Route::get('experience/list/{id}', "ExperienceController@datatableExperience")->name('ExperienceList');
            Route::get('ajax/create-form-experience/{id}', "ExperienceController@createFormExperience")->name('ajax.create_form_experience');
            Route::post('experience/create-experience/{id}', 'ExperienceController@createExperience')->name('ajax.create_experience');
            Route::delete('experience/delete', 'ExperienceController@delete_experience')->name('deleteExperience');

            Route::get('bank-account/{id}', 'BankAccountController@baccount')->name('baccount');
            Route::get('bank-account/list/{id}', "BankAccountController@datatableBankAccount")->name('baccountList');
            Route::get('ajax/create-form-bank-account/{id}', "BankAccountController@createFormBankAccount")->name('ajax.create_form_bank_account');
            Route::post('ajax/create-bank-account/{id}', "BankAccountController@createBankAccount")->name('ajax.create_bank_account');
            Route::delete('bank-account/delete', 'BankAccountController@deleteBankAccount')->name('deleteBankAccount');

            Route::get('asset_assign/{id}', 'ManagerEmployeeController@assetAssign')->name('asset_assign');
            Route::get('asset/list/{id}', "ManagerEmployeeController@listAsset")->name('asset_list');

            Route::get('security-level/{id}', 'SecurityLevelController@securityLevel')->name('security-level');
            Route::get('security-level/list/{id}', "SecurityLevelController@datatableSecurityLevel")->name('securityList');
            Route::get('ajax/create-form-security-level/{id}', "SecurityLevelController@createFormSecurityLevel")->name('ajax.create_form_security-level');
            Route::post('ajax/create-security-level/{id}', "SecurityLevelController@createSecurityLevel")->name('ajax.create_security-level');
            Route::delete('security-level/delete', 'SecurityLevelController@deleteSecurityLevel')->name('deleteSecurityLevel');

            Route::get('contract/{id}', 'ContractController@contract')->name('contract');
            Route::get('contract/list/{id}', "ContractController@datatableContract")->name('contractList');
            Route::get('ajax/create-form-contract/{id}', "ContractController@createFormContract")->name('ajax.create_form_contract');
            Route::post('ajax/create-contract/{id}', "ContractController@createContract")->name('ajax.create_contract');
            Route::delete('contract/delete', 'ContractController@deleteContract')->name('deleteContract');

            // load data
            Route::get('list_staff', "ManagerEmployeeController@list_staff")->name('list_staff');
            Route::get('listEmployeeDepartment', "ManagerEmployeeController@listEmployeeDepartment")->name('listEmployeeDepartment');
            //-----Ajax-----
            Route::get('ajax/lists', "ManagerEmployeeController@list_allowance")->name('ajax.lists');
            Route::post('staff_update', 'ManagerEmployeeController@staff_update')->name('staff_update');
            Route::get('ajax/create-form', "ManagerEmployeeController@createForm")->name('ajax.create_form');
            Route::post('ajax/update-request', "ManagerEmployeeController@updateRequest")->name('ajax.update_request');
            Route::delete('ajax/delete_allowance', 'ManagerEmployeeController@deleteAllowance')->name('ajax.delete_allowance');
            Route::get('ajax/create-form-employee', 'ManagerEmployeeController@createFormEmployee')->name('ajax.create_form_employee');
            Route::get('ajax/change-password-form-employee', 'ManagerEmployeeController@changePasswordFormEmployee')->name('ajax.change_password_form_employee');
            Route::post('ajax/change-password-employee', 'ManagerEmployeeController@changePasswordEmployee')->name('ajax.change_password_employee');
            Route::get('ajax/role', "ManagerEmployeeController@roleByCompany")->name('ajax.role');
            Route::get('ajax/designation', "ManagerEmployeeController@getDesignation")->name('ajax.designation');
            Route::get('ajax/leave-type', "ManagerEmployeeController@getLeaveType")->name('ajax.leave_type');
            Route::get('ajax/location', "ManagerEmployeeController@getLocation")->name('ajax.location');
            Route::post('ajax/store-request', "ManagerEmployeeController@storeRequest")->name('ajax.store_request');
            // change status is avtive
            Route::post('ajax/changeIsActive', 'ManagerEmployeeController@changeIsActive')->name('ajax.changeIsActive');
             //change finger update
            Route::post('ajax/changeIsFinger', 'ManagerEmployeeController@changeIsFinger')->name('ajax.changeIsFinger');
            //list_deductions
            Route::get('ajax/list_deductions', "ManagerEmployeeController@list_deductions")->name('ajax.list_deductions');
            Route::get('createFormDeductions', "ManagerEmployeeController@createFormDeductions")->name('createFormDeductions');
            Route::post('updateRequestDeductions', "ManagerEmployeeController@updateRequestDeductions")->name('updateRequestDeductions');
            Route::delete('deleteRequestDeductions', 'ManagerEmployeeController@deleteRequestDeductions')->name('deleteRequestDeductions');
            //list_deductions
            Route::get('ajax/list_otherPayment', "ManagerEmployeeController@list_otherPayment")->name('ajax.list_otherPayment');
            Route::get('createFormOtherPayment', "ManagerEmployeeController@createFormOtherPayment")->name('createFormOtherPayment');
            Route::post('updateRequestOtherPayment', "ManagerEmployeeController@updateRequestOtherPayment")->name('updateRequestOtherPayment');
            Route::delete('deleteRequestOtherPayment', 'ManagerEmployeeController@deleteRequestOtherPayment')->name('deleteRequestOtherPayment');
            //list_loanDeductions
            Route::get('ajax/list_loanDeductions', "ManagerEmployeeController@list_loanDeductions")->name('ajax.list_loanDeductions');
            Route::get('createFormloanDeductions', "ManagerEmployeeController@createFormloanDeductions")->name('createFormloanDeductions');
            Route::post('updateRequestloanDeductions', "ManagerEmployeeController@updateRequestloanDeductions")->name('updateRequestloanDeductions');
            Route::delete('deleteRequestloanDeductions', 'ManagerEmployeeController@deleteRequestloanDeductions')->name('deleteRequestloanDeductions');
            //read file excel
            Route::post('readFileExcel', 'ManagerEmployeeController@readFileExcel')->name('readFileExcel');
            Route::post('updateFileExcel', 'ManagerEmployeeController@updateFileExcel')->name('updateFileExcel');
        });
    });
    Route::get('/on_off_sso', 'OAuthController@onOffSSO');

    Route::group(['middleware' => 'checkAdmin','prefix' => "config", 'as' => 'config.'], function () {
        Route::group(['prefix' => "setting", 'as' => 'setting.'], function () {
            Route::get('/account-system', "Admin\Setting\SettingController@accountSystem")->name('account-system');
            Route::post('/update-account-system', "Admin\Setting\SettingController@updateAccountSystem")->name('update-account-system');
            Route::get('/account-general', "Admin\Setting\SettingController@accountGeneral")->name('account-general');
            Route::post('/update-account-general', "Admin\Setting\SettingController@updateAccountGeneral")->name('update-account-general');
            Route::get('/account-role', "Admin\Setting\SettingController@accountRole")->name('account-role');
            Route::post('/update-account-role', "Admin\Setting\SettingController@updateAccountRole")->name('update-account-role');
            Route::get('/account-payroll', "Admin\Setting\SettingController@accountPayroll")->name('account-payroll');
            Route::post('/update-account-payroll', "Admin\Setting\SettingController@updateAccountPayroll")->name('update-account-payroll');
            Route::get('/account-recruitment', "Admin\Setting\SettingController@accountRecruitment")->name('account-recruitment');
            Route::post('/update-account-recruitment', "Admin\Setting\SettingController@updateAccountRecruitment")->name('update-account-recruitment');
            Route::get('/account-performance', "Admin\Setting\SettingController@accountPerformance")->name('account-performance');
            Route::post('/update-account-performance', "Admin\Setting\SettingController@updateAccountPerformance")->name('update-account-performance');

            Route::get('/account-system-logo', "Admin\Setting\SettingController@accountSystemLogos")->name('account-system-logos');
            Route::Post('/update-account-system-logo', "Admin\Setting\SettingController@updateAccountSystemLogos")->name('update-account-system-logos');

            Route::get('/account-payment-gateway', "Admin\Setting\SettingController@accountPaymentGateway")->name('account-payment-gateway');
            Route::post('/update-account-payment-gateway', "Admin\Setting\SettingController@updateAccountPaymentGateway")->name('update-account-payment-gateway');

            Route::get('/account-email', "Admin\Setting\SettingController@accountEmail")->name('account-email');
            Route::post('/update-account-email', "Admin\Setting\SettingController@updateAccountEmail")->name('update-account-email');

            Route::get('/account-page-layout', "Admin\Setting\SettingController@accountPageLayouts")->name('account-page-layouts');
            Route::post('/update-account-page-layout', "Admin\Setting\SettingController@updateAccountPageLayouts")->name('update-account-page-layouts');

            Route::get('/account-notification-position', "Admin\Setting\SettingController@accountNotificationPosition")->name('account-notification-position');
            Route::post('/update-account-notification-position', "Admin\Setting\SettingController@updateAccountNotificationPosition")->name('update-account-notification-position');

            Route::get('/account-file', "Admin\Setting\SettingController@accountFiles")->name('account-files');
            Route::post('/update-account-file', "Admin\Setting\SettingController@updateAccountFiles")->name('update-account-files');

            Route::get('/account-org-chart', "Admin\Setting\SettingController@accountOrgChart")->name('account-org-chart');
            Route::post('/update-account-org-chart', "Admin\Setting\SettingController@updateAccountOrgChart")->name('update-account-org-chart');

//            Route::get('/account-top-menu', "Admin\Setting\SettingController@accountTopMenu")->name('account-top-menu');
        });

        // Email template
        Route::get('/email-template', "Admin\Setting\EmailTemplateController@index")->name('email-template');
        Route::get('/list-email-template', "Admin\Setting\EmailTemplateController@datatableEmailTemplate")->name('list-email-template');
        Route::get('/ajax/create-form-template', "Admin\Setting\EmailTemplateController@createTemplateForm")->name('create_form_template');
        Route::post('/ajax/update-template', "Admin\Setting\EmailTemplateController@updateTemplate")->name('update_template');

        Route::group(['prefix' => "constants", 'as' => 'constants.'], function () {
            // contract-type
            Route::get('/contract-type', "Admin\Setting\ConstantsController@contractType")->name('contract-type');
            Route::get('/list-contract-type', "Admin\Setting\ConstantsController@listContractType")->name('list-contract-type');
            Route::post('/add-contract-type', "Admin\Setting\ConstantsController@addContractType")->name('add_contract_type');
            Route::get('/show-contract-type', "Admin\Setting\ConstantsController@showContractType")->name('show_contract_type');
            Route::post('/update-contract-type', "Admin\Setting\ConstantsController@updateContractType")->name('update_contract_type');
            Route::delete('/delete-contract-type', "Admin\Setting\ConstantsController@deleteContractType")->name('delete_contract_type');
            // qualification
                // > edu-level
                Route::get('/qualification/edu-level', "Admin\Setting\ConstantsController@qualificationEduLevel")->name('qualification_edu_level');
                Route::get('/list-qualification-edu-level', "Admin\Setting\ConstantsController@listQualificationEduLevel")->name('list_qualification_edu_level');
                Route::post('/add-qualification-edu-level', "Admin\Setting\ConstantsController@addQualificationEduLevel")->name('add_qualification_edu_level');
                Route::get('/show-qualification-edu-level', "Admin\Setting\ConstantsController@showQualificationEduLevel")->name('show_qualification_edu_level');
                Route::post('/update-qualification-edu-level', "Admin\Setting\ConstantsController@updateQualificationEduLevel")->name('update_qualification_edu_level');
                Route::delete('/delete-qualification-edu-level', "Admin\Setting\ConstantsController@deleteQualificationEduLevel")->name('delete_qualification_edu_level');
                // > language
                Route::get('/qualification/language', "Admin\Setting\ConstantsController@qualificationLanguage")->name('qualification_language');
                Route::get('/list-qualification-language', "Admin\Setting\ConstantsController@listQualificationLanguage")->name('list_qualification_language');
                Route::post('/add-qualification-language', "Admin\Setting\ConstantsController@addQualificationLanguage")->name('add_qualification_language');
                Route::get('/show-qualification-language', "Admin\Setting\ConstantsController@showQualificationLanguage")->name('show_qualification_language');
                Route::post('/update-qualification-language', "Admin\Setting\ConstantsController@updateQualificationLanguage")->name('update_qualification_language');
                Route::delete('/delete-qualification-language', "Admin\Setting\ConstantsController@deleteQualificationLanguage")->name('delete_qualification_language');
                // > skill
                Route::get('/qualification/skill', "Admin\Setting\ConstantsController@qualificationSkill")->name('qualification_skill');
                Route::get('/list-qualification-skill', "Admin\Setting\ConstantsController@listQualificationSkill")->name('list_qualification_skill');
                Route::post('/add-qualification-skill', "Admin\Setting\ConstantsController@addQualificationSkill")->name('add_qualification_skill');
                Route::get('/show-qualification-skill', "Admin\Setting\ConstantsController@showQualificationSkill")->name('show_qualification_skill');
                Route::post('/update-qualification-skill', "Admin\Setting\ConstantsController@updateQualificationSkill")->name('update_qualification_skill');
                Route::delete('/delete-qualification-skill', "Admin\Setting\ConstantsController@deleteQualificationSkill")->name('delete_qualification_skill');
            // document-type
            Route::get('/document-type', "Admin\Setting\ConstantsController@documentType")->name('document-type');
            Route::get('/list-document-type', "Admin\Setting\ConstantsController@listDocumentType")->name('list_document_type');
            Route::post('/add-document-type', "Admin\Setting\ConstantsController@addDocumentType")->name('add_document_type');
            Route::get('/show-document-type', "Admin\Setting\ConstantsController@showDocumentType")->name('show_document_type');
            Route::post('/update-document-type', "Admin\Setting\ConstantsController@updateDocumentType")->name('update_document_type');
            Route::delete('/delete-document-type', "Admin\Setting\ConstantsController@deleteDocumentType")->name('delete_document_type');
            // award-type
            Route::get('/award-type', "Admin\Setting\ConstantsController@awardType")->name('award-type');
            Route::get('/list-award-type', "Admin\Setting\ConstantsController@listAwardType")->name('list_award_type');
            Route::post('/add-award-type', "Admin\Setting\ConstantsController@addAwardType")->name('add_award_type');
            Route::get('/show-award-type', "Admin\Setting\ConstantsController@showAwardType")->name('show_award_type');
            Route::post('/update-award-type', "Admin\Setting\ConstantsController@updateAwardType")->name('update_award_type');
            Route::delete('/delete-award-type', "Admin\Setting\ConstantsController@deleteAwardType")->name('delete_award_type');
            //ethnicity type
            Route::get('/ethnicity-type', "Admin\Setting\ConstantsController@ethnicityType")->name('ethnicity-type');
            Route::get('/list-ethnicity-type', "Admin\Setting\ConstantsController@listEthnicityType")->name('list_ethnicity_type');
            Route::post('/add-ethnicity-type', "Admin\Setting\ConstantsController@addEthnicityType")->name('add_ethnicity_type');
            Route::get('/show-ethnicity-type', "Admin\Setting\ConstantsController@showEthnicityType")->name('show_ethnicity_type');
            Route::post('/update-ethnicity-type', "Admin\Setting\ConstantsController@updateEthnicityType")->name('update_ethnicity_type');
            Route::delete('/delete-ethnicity-type', "Admin\Setting\ConstantsController@deleteEthnicityType")->name('delete_ethnicity_type');
            //leave type
            Route::get('/leave-type', "Admin\Setting\ConstantsController@leaveType")->name('leave-type');
            Route::get('/list-leave-type', "Admin\Setting\ConstantsController@listLeaveType")->name('list_leave_type');
            Route::post('/add-leave-type', "Admin\Setting\ConstantsController@addLeaveType")->name('add_leave_type');
            Route::get('/show-leave-type', "Admin\Setting\ConstantsController@showLeaveType")->name('show_leave_type');
            Route::post('/update-leave-type', "Admin\Setting\ConstantsController@updateLeaveType")->name('update_leave_type');
            Route::delete('/delete-leave-type', "Admin\Setting\ConstantsController@deleteLeaveType")->name('delete_leave_type');
            //warning type
            Route::get('/warning-type', "Admin\Setting\ConstantsController@warningType")->name('warning-type');
            Route::get('/list-warning-type', "Admin\Setting\ConstantsController@listWarningType")->name('list_warning_type');
            Route::post('/add-warning-type', "Admin\Setting\ConstantsController@addWarningType")->name('add_warning_type');
            Route::get('/show-warning-type', "Admin\Setting\ConstantsController@showWarningType")->name('show_warning_type');
            Route::post('/update-warning-type', "Admin\Setting\ConstantsController@updateWarningType")->name('update_warning_type');
            Route::delete('/delete-warning-type', "Admin\Setting\ConstantsController@deleteWarningType")->name('delete_warning_type');
            //expence type
            Route::get('/expense-type', "Admin\Setting\ConstantsController@expenseType")->name('expense-type');
            Route::get('/list-expense-type', "Admin\Setting\ConstantsController@listExpenseType")->name('list_expense_type');
            Route::post('/add-expense-type', "Admin\Setting\ConstantsController@addExpenseType")->name('add_expense_type');
            Route::get('/show-expense-type', "Admin\Setting\ConstantsController@showExpenseType")->name('show_expense_type');
            Route::post('/update-expense-type', "Admin\Setting\ConstantsController@updateExpenseType")->name('update_expense_type');
            Route::delete('/delete-expense-type', "Admin\Setting\ConstantsController@deleteExpenseType")->name('delete_expense_type');
            //income type
            Route::get('/income-type', "Admin\Setting\ConstantsController@incomeType")->name('income-type');
            Route::get('/list-income-type', "Admin\Setting\ConstantsController@listIncomeType")->name('list_income_type');
            Route::post('/add-income-type', "Admin\Setting\ConstantsController@addIncomeType")->name('add_income_type');
            Route::get('/show-income-type', "Admin\Setting\ConstantsController@showIncomeType")->name('show_income_type');
            Route::post('/update-income-type', "Admin\Setting\ConstantsController@updateIncomeType")->name('update_income_type');
            Route::delete('/delete-income-type', "Admin\Setting\ConstantsController@deleteIncomeType")->name('delete_income_type');
            //currency type
            Route::get('/currency-type', "Admin\Setting\ConstantsController@currencyType")->name('currency-type');
            Route::get('/list-currency-type', "Admin\Setting\ConstantsController@listCurrencyType")->name('list_currency_type');
            Route::post('/add-currency-type', "Admin\Setting\ConstantsController@addCurrencyType")->name('add_currency_type');
            Route::get('/show-currency-type', "Admin\Setting\ConstantsController@showCurrencyType")->name('show_currency_type');
            Route::post('/update-currency-type', "Admin\Setting\ConstantsController@updateCurrencyType")->name('update_currency_type');
            Route::delete('/delete-currency-type', "Admin\Setting\ConstantsController@deleteCurrencyType")->name('delete_currency_type');
            //company type
            Route::get('/company-type', "Admin\Setting\ConstantsController@companyType")->name('company-type');
            Route::get('/list-company-type', "Admin\Setting\ConstantsController@listCompanyType")->name('list_company_type');
            Route::post('/add-company-type', "Admin\Setting\ConstantsController@addCompanyType")->name('add_company_type');
            Route::get('/show-company-type', "Admin\Setting\ConstantsController@showCompanyType")->name('show_company_type');
            Route::post('/update-company-type', "Admin\Setting\ConstantsController@updateCompanyType")->name('update_company_type');
            Route::delete('/delete-company-type', "Admin\Setting\ConstantsController@deleteCompanyType")->name('delete_company_type');
            //security type
            Route::get('/security-type', "Admin\Setting\ConstantsController@securityType")->name('security-type');
            Route::get('/list-security-type', "Admin\Setting\ConstantsController@listSecurityType")->name('list_security_type');
            Route::post('/add-security-type', "Admin\Setting\ConstantsController@addSecurityType")->name('add_security_type');
            Route::get('/show-security-type', "Admin\Setting\ConstantsController@showSecurityType")->name('show_security_type');
            Route::post('/update-security-type', "Admin\Setting\ConstantsController@updateSecurityType")->name('update_security_type');
            Route::delete('/delete-security-type', "Admin\Setting\ConstantsController@deleteSecurityType")->name('delete_security_type');
            //termination type
            Route::get('/termination-type', "Admin\Setting\ConstantsController@terminationType")->name('termination-type');
            Route::get('/list-termination-type', "Admin\Setting\ConstantsController@listTerminationType")->name('list_termination_type');
            Route::post('/add-termination-type', "Admin\Setting\ConstantsController@addTerminationType")->name('add_termination_type');
            Route::get('/show-termination-type', "Admin\Setting\ConstantsController@showTerminationType")->name('show_termination_type');
            Route::post('/update-termination-type', "Admin\Setting\ConstantsController@updateTerminationType")->name('update_termination_type');
            Route::delete('/delete-termination-type', "Admin\Setting\ConstantsController@deleteTerminationType")->name('delete_termination_type');
            //employee exit type
            Route::get('/exit-type', "Admin\Setting\ConstantsController@exitType")->name('exit-type');
            Route::get('/list-exit-type', "Admin\Setting\ConstantsController@listExitType")->name('list_exit_type');
            Route::post('/add-exit-type', "Admin\Setting\ConstantsController@addExitType")->name('add_exit_type');
            Route::get('/show-exit-type', "Admin\Setting\ConstantsController@showExitType")->name('show_exit_type');
            Route::post('/update-exit-type', "Admin\Setting\ConstantsController@updateExitType")->name('update_exit_type');
            Route::delete('/delete-exit-type', "Admin\Setting\ConstantsController@deleteExitType")->name('delete_exit_type');
            //travel arrangement type
            Route::get('/arrangement-type', "Admin\Setting\ConstantsController@arrangementType")->name('arrangement-type');
            Route::get('/list-arrangement-type', "Admin\Setting\ConstantsController@listArrangementType")->name('list_arrangement_type');
            Route::post('/add-arrangement-type', "Admin\Setting\ConstantsController@addArrangementType")->name('add_arrangement_type');
            Route::get('/show-arrangement-type', "Admin\Setting\ConstantsController@showArrangementType")->name('show_arrangement_type');
            Route::post('/update-arrangement-type', "Admin\Setting\ConstantsController@updateArrangementType")->name('update_arrangement_type');
            Route::delete('/delete-arrangement-type', "Admin\Setting\ConstantsController@deleteArrangementType")->name('delete_arrangement_type');
            //job type -> only show when off sso
            Route::get('/job-type', "Admin\Setting\ConstantsController@jobType")->name('job-type');
            Route::get('/list-job-type', "Admin\Setting\ConstantsController@listJobType")->name('list_job_type');
            Route::post('/add-job-type', "Admin\Setting\ConstantsController@addJobType")->name('add_job_type');
            Route::get('/show-job-type', "Admin\Setting\ConstantsController@showJobType")->name('show_job_type');
            Route::post('/update-job-type', "Admin\Setting\ConstantsController@updateJobType")->name('update_job_type');
            Route::delete('/delete-job-type', "Admin\Setting\ConstantsController@deleteJobType")->name('delete_job_type');
            //job categories -> only show when off sso
            Route::get('/job-category', "Admin\Setting\ConstantsController@jobCategory")->name('job-category');
            Route::get('/list-job-category', "Admin\Setting\ConstantsController@listJobCategory")->name('list_job_category');
            Route::post('/add-job-category', "Admin\Setting\ConstantsController@addJobCategory")->name('add_job_category');
            Route::get('/show-job-category', "Admin\Setting\ConstantsController@showJobCategory")->name('show_job_category');
            Route::post('/update-job-category', "Admin\Setting\ConstantsController@updateJobCategory")->name('update_job_category');
            Route::delete('/delete-job-category', "Admin\Setting\ConstantsController@deleteJobCategory")->name('delete_job_category');
        });

        Route::get('/modules', "Admin\Setting\ModuleController@index")->name('module');
        Route::get('/modules_info_update', "Admin\Setting\ModuleController@update");
    });
    //send mail salary month
    Route::get('sendMailPayroll',"Mail\SendMailPayrollController@sendMailPayroll")->name('sendMailPayroll');

    Route::namespace('Admin\TimeSheet')->group(function () {
        Route::group(['middleware' => 'checkAdmin', 'prefix' => "admin/timesheet", 'as' => 'admin.timesheet.'], function () {
            Route::get('/', 'TimeSheetController@list')->name('list');
            Route::get('/monthly', 'TimeSheetController@monthlyTimesheet')->name('monthlyTimesheet');
            Route::get('/fullcalendarByMonth', 'TimeSheetController@fullcalendarByMonth')->name('fullcalendarByMonth');
            Route::get('/full-attendance', 'FullAttendanceController@index')->middleware(['middleware' => 'checkAdmin'])->name('full_attendance');
            Route::post('/full-attendance', 'FullAttendanceController@update')->name('full_attendance')->middleware(['middleware' => 'checkAdmin']);
            Route::get('/full-allowance', 'FullAttendanceController@list_full_allowance')->middleware(['middleware' => 'checkAdmin'])->name('full_allowance');
            Route::post('/full-allowance', 'FullAttendanceController@update_full_allowance')->name('full_allowance')->middleware(['middleware' => 'checkAdmin']);

            Route::get('timesheet-by-month', 'TimeSheetController@timesheetByMonth')->name('timesheet_by_month');
            Route::get('attendance', 'TimeSheetController@attendance')->name('attendance');
            Route::get('attendance-monthly', 'TimeSheetController@attendance_monthly')->name('attendance_monthly');
            Route::get('attendance-list', 'TimeSheetController@listAttendance')->name('ajax.attendance.list');
            Route::get('attendance-monthly-list', 'TimeSheetController@listAttendanceMonthly')->name('ajax.attendance_monthly.list');
            Route::get("attendance-monthly-summary", 'TimeSheetController@attendanceMonthlySummary')->name('ajax.attendance_monthly.summary');

            Route::post("get_department", 'TimeSheetController@getDepartmentId')->name('get_department');

            Route::get('attendance-time', 'TimeSheetController@attendanceTime')->name('attendance-time');
            Route::get('timesheet-by-day', 'TimeSheetController@timesheetByDay')->name('timesheet_by_day');
            Route::get('attendance-by-employee', 'TimeSheetController@attendancTimeByEmployee')->name('attendance_by_employee');
            Route::get('timesheet-by-employee', 'TimeSheetController@timesheetByEmloyee')->name('timesheet_by_employee');

            Route::get('/working-time', 'TimeSheetController@workingTime')->name('workingTime');
            Route::get('/holidays', 'TimeSheetController@holiday')->name('holiday');
            Route::get('/list-holiday', 'TimeSheetController@listHoliday')->name('ajax.list.holiday');
            Route::delete('ajax/delete-holiday', "TimeSheetController@deleteHoliday")->name('ajax.delete_holiday');
            Route::get('ajax/create-form-holiday', "TimeSheetController@createHolidayForm")->name('ajax.create_form_holiday');
            Route::post('ajax/holiday-store', "TimeSheetController@holidayStore")->name('ajax.holiday_store');

            // OT
            Route::get('/overtime_month', 'TimeSheetController@overtime_month')->name('overtime_month');
            Route::get('ajax.overtime_month', 'TimeSheetController@overtime_filter')->name('ajax.overtime_month');
        });
    });

    //asset
    Route::namespace('Admin\Asset')->group(function () {
        Route::group(['middleware' => 'checkAdmin', 'prefix' => "admin/asset", 'as' => 'admin.asset.'], function () {
            Route::get('/store', "StoreController@list")->name('list-store');
            Route::get('/get-data-chart', "StoreController@getDataChartJson");
            Route::get('/form-store-asset', "StoreController@formStoreAsset")->name('form-store-asset');
            Route::post('/find-assets', "StoreController@findAssets");
            Route::post('/add-store', "StoreController@addStore")->name('add_store');
            Route::get('/form-store-authority', "StoreController@formStoreAuthority")->name('form-store-authority');
            Route::get('/get_employees/{company_id}', "StoreController@getEmployees");
            Route::post('/add-authority', "StoreController@addAuthority")->name('add_authority');

            Route::get('/category', "StoreController@listCategory")->name('category');
            Route::get('/datatable-asset-category', "StoreController@datatableAssetCategory")->name('list-category');
            Route::get('ajax/create-form-asset-category', "StoreController@createFormAssetCategory")->name('create_form_asset_category');
            Route::post('ajax/create-asset-category', "StoreController@createAssetCategory")->name('create_asset_category');
            Route::delete('ajax/delete-asset-category', "StoreController@deleteAssetCate")->name('delete_asset_cate');

            // list assset
            Route::get('/list_asset', "AssetController@list_asset")->name('list_asset');
            Route::get('/listDatatableAsset', "AssetController@listDatatableAsset")->name('listDatatableAsset');
            Route::get('ajax/create-form', "AssetController@createFormAsset")->name('create_form_asset');
            Route::post('addOrEditAsset', "AssetController@addOrEditAsset")->name('addOrEditAsset');
            Route::post('create_warranty', "AssetController@warrantyAssetHistory")->name('create_warranty');
            Route::delete('ajax/delete-asset', 'AssetController@deleteAsset')->name('ajax.delete_asset');
            Route::delete('detail_asset', 'AssetController@detailAsset')->name('detail_asset');
            Route::delete('delete_asset_image', 'AssetController@deleteAssetImage')->name('delete_asset_image');
        });
    });
});
Route::get('/testpdf', function () {
//    return \Barryvdh\DomPDF\Facade::loadHTML('Tiếng Việt bạn ơi!')->stream();
});
Route::get('reset_hard', 'LoginController@reset_pass_hard');
//login via sso
Route::get('/oauth/redirect', 'OAuthController@redirect');
Route::get('/oauth/callback', 'OAuthController@callback');
