<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsColumnsThaolx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'advance_salaries',
            'announcements',
            'assets',
            'assets_categories',
            'attendance_daily',
            'attendance_log',
            'attendance_machine',
            'attendance_time',
            'attendance_time_request',
            'awards',
            'award_type',
            'chat_messages',
            'clients',
            'companies',
            'company_documents',
            'company_info',
            'company_policy',
            'company_type',
            'contract_type',
            'countries',
            'currencies',
            'currency_converter',
            'database_backup',
            'datcom',
            'datcom_employee_order',
            'datcom_monan',
            'departments',
            'designations',
            'document_type',
            'email_configuration',
            'email_template',
            'employees',
            'employees_tmp_payslip',
            'employee_bankaccount',
            'employee_complaints',
            'employee_contacts',
            'employee_contract',
            'employee_documents',
            'employee_exit',
            'employee_exit_type',
            'employee_immigration',
            'employee_leave',
            'employee_location',
            'employee_promotions',
            'employee_qualification',
            'employee_resignations',
            'employee_security_level',
            'employee_shift',
            'employee_terminations',
            'employee_transfer',
            'employee_travels',
            'employee_warnings',
            'employee_work_experience',
            'ethnicity_type',
            'events',
            'expenses',
            'expense_type',
            'file_manager',
            'file_manager_settings',
            'finance_bankcash',
            'finance_deposit',
            'finance_expense',
            'finance_payees',
            'finance_payers',
            'finance_transaction',
            'finance_transactions',
            'finance_transfer',
            'goal_tracking',
            'goal_tracking_type',
            'holidays',
            'hourly_templates',
            'hrsale_invoices',
            'hrsale_invoices_items',
            'hrsale_module_attributes',
            'hrsale_module_attributes_select_value',
            'hrsale_module_attributes_values',
            'hrsale_notificaions',
            'hrsale_quotes',
            'hrsale_quotes_items',
            'income_categories',
            'jobs',
            'job_applications',
            'job_categories',
            'job_interviews',
            'job_pages',
            'job_type',
            'kpi_incidental',
            'kpi_maingoals',
            'kpi_variable',
            'languages',
            'leads',
            'leads_followup',
            'leave_applications',
            'leave_type',
            'make_payment',
            'meetings',
            'office_location',
            'office_shift',
            'payment_method',
            'payroll_custom_fields',
            'performance_appraisal',
            'performance_appraisal_options',
            'performance_indicator',
            'performance_indicator_options',
            'projects',
            'projects_attachment',
            'projects_bugs',
            'projects_discussion',
            'projects_timelogs',
            'project_variations',
            'qualification_education_level',
            'qualification_language',
            'qualification_skill',
            'quoted_projects',
            'quoted_projects_attachment',
            'quoted_projects_discussion',
            'quoted_projects_timelogs',
            'recruitment_pages',
            'recruitment_subpages',
            'salary_allowances',
            'salary_bank_allocation',
            'salary_commissions',
            'salary_loan_deductions',
            'salary_other_payments',
            'salary_overtime',
            'salary_payslips',
            'salary_payslip_allowances',
            'salary_payslip_commissions',
            'salary_payslip_loan',
            'salary_payslip_other_payments',
            'salary_payslip_overtime',
            'salary_payslip_statutory_deductions',
            'salary_statutory_deductions',
            'salary_templates',
            'security_level',
            'sub_departments',
            'support_tickets',
            'support_tickets_employees',
            'support_ticket_files',
            'system_setting',
            'tasks',
            'tasks_attachment',
            'tasks_comments',
            'task_categories',
            'tax_types',
            'termination_type',
            'theme_settings',
            'tickets_attachment',
            'tickets_comments',
            'trainers',
            'training',
            'training_types',
            'travel_arrangement_type',
            'users',
            'user_roles',
            'warning_type'
        ];
        foreach($tables as $table_name) {
            if (Schema::hasColumn($table_name, 'created_at'))
            {
                Schema::table($table_name, function (Blueprint $table) {
                    $table->dropColumn(['created_at']);
                });
            }
            if (Schema::hasColumn($table_name, 'updated_at')){
                Schema::table($table_name, function (Blueprint $table) {
                    $table->dropColumn(['updated_at']);
                });
            }
            Schema::table($table_name, function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
