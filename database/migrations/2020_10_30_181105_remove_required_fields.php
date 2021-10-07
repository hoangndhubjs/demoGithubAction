<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRequiredFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer("office_shift_id")->nullable()->change();
            $table->integer("reports_to")->nullable()->change();
            $table->integer("pincode")->nullable()->change();
            $table->string("date_of_birth")->nullable()->change();
            $table->string("gender")->nullable()->change();
            $table->integer("e_status")->nullable()->change();
            $table->integer("department_id")->nullable()->change();
            $table->integer("sub_department_id")->nullable()->change();
            $table->integer("designation_id")->nullable()->change();
            $table->integer("location_id")->nullable()->change();
            $table->string("view_companies_id")->nullable()->change();
            $table->string("salary_template")->nullable()->change();
            $table->integer("hourly_grade_id")->nullable()->change();
            $table->integer("monthly_grade_id")->nullable()->change();
            $table->string("date_of_leaving")->nullable()->change();
            $table->string("marital_status")->nullable()->change();
            $table->string("salary")->nullable()->change();
            $table->integer("wages_type")->nullable()->change();
            $table->string("basic_salary")->nullable()->change();
            $table->string("daily_wages")->nullable()->change();
            $table->string("salary_ssempee")->nullable()->change();
            $table->string("salary_income_tax")->nullable()->change();
            $table->string("salary_overtime")->nullable()->change();
            $table->string("salary_commission")->nullable()->change();
            $table->string("salary_claims")->nullable()->change();
            $table->string("salary_paid_leave")->nullable()->change();
            $table->string("salary_director_fees")->nullable()->change();
            $table->string("salary_bonus")->nullable()->change();
            $table->string("salary_advance_paid")->nullable()->change();
            $table->mediumtext("address")->nullable()->change();
            $table->string("state")->nullable()->change();
            $table->string("city")->nullable()->change();
            $table->string("zipcode")->nullable()->change();
            $table->mediumtext("profile_background")->nullable()->change();
            $table->mediumtext("resume")->nullable()->change();
            $table->string("skype_id")->nullable()->change();
            $table->mediumtext("facebook_link")->nullable()->change();
            $table->mediumtext("twitter_link")->nullable()->change();
            $table->mediumtext("blogger_link")->nullable()->change();
            $table->mediumtext("linkdedin_link")->nullable()->change();
            $table->mediumtext("google_plus_link")->nullable()->change();
            $table->string("instagram_link")->nullable()->change();
            $table->string("pinterest_link")->nullable()->change();
            $table->string("youtube_link")->nullable()->change();
            $table->string("last_login_date")->nullable()->change();
            $table->string("last_logout_date")->nullable()->change();
            $table->string("last_login_ip")->nullable()->change();
            $table->integer("is_logged_in")->nullable()->change();
            $table->integer("online_status")->nullable()->change();
            $table->string("fixed_header")->nullable()->change();
            $table->string("compact_sidebar")->nullable()->change();
            $table->string("boxed_wrapper")->nullable()->change();
            $table->string("leave_categories")->nullable()->change();
            $table->integer("ethnicity_type")->nullable()->change();
            $table->integer("nationality_id")->nullable()->change();
            $table->integer("citizenship_id")->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer("office_shift_id")->nullable(true)->change();
            $table->integer("reports_to")->nullable(true)->change();
            $table->integer("pincode")->nullable(true)->change();
            $table->string("date_of_birth")->nullable(true)->change();
            $table->string("gender")->nullable(true)->change();
            $table->integer("e_status")->nullable(true)->change();
            $table->integer("department_id")->nullable(true)->change();
            $table->integer("sub_department_id")->nullable(true)->change();
            $table->integer("designation_id")->nullable(true)->change();
            $table->integer("location_id")->nullable(true)->change();
            $table->string("view_companies_id")->nullable(true)->change();
            $table->string("salary_template")->nullable(true)->change();
            $table->integer("hourly_grade_id")->nullable(true)->change();
            $table->integer("monthly_grade_id")->nullable(true)->change();
            $table->string("date_of_leaving")->nullable(true)->change();
            $table->string("marital_status")->nullable(true)->change();
            $table->string("salary")->nullable(true)->change();
            $table->integer("wages_type")->nullable(true)->change();
            $table->string("basic_salary")->nullable(true)->change();
            $table->string("daily_wages")->nullable(true)->change();
            $table->string("salary_ssempee")->nullable(true)->change();
            $table->string("salary_income_tax")->nullable(true)->change();
            $table->string("salary_overtime")->nullable(true)->change();
            $table->string("salary_commission")->nullable(true)->change();
            $table->string("salary_claims")->nullable(true)->change();
            $table->string("salary_paid_leave")->nullable(true)->change();
            $table->string("salary_director_fees")->nullable(true)->change();
            $table->string("salary_bonus")->nullable(true)->change();
            $table->string("salary_advance_paid")->nullable(true)->change();
            $table->mediumtext("address")->nullable(true)->change();
            $table->string("state")->nullable(true)->change();
            $table->string("city")->nullable(true)->change();
            $table->string("zipcode")->nullable(true)->change();
            $table->mediumtext("profile_background")->nullable(true)->change();
            $table->mediumtext("resume")->nullable(true)->change();
            $table->string("skype_id")->nullable(true)->change();
            $table->mediumtext("facebook_link")->nullable(true)->change();
            $table->mediumtext("twitter_link")->nullable(true)->change();
            $table->mediumtext("blogger_link")->nullable(true)->change();
            $table->mediumtext("linkdedin_link")->nullable(true)->change();
            $table->mediumtext("google_plus_link")->nullable(true)->change();
            $table->string("instagram_link")->nullable(true)->change();
            $table->string("pinterest_link")->nullable(true)->change();
            $table->string("youtube_link")->nullable(true)->change();
            $table->string("last_login_date")->nullable(true)->change();
            $table->string("last_logout_date")->nullable(true)->change();
            $table->string("last_login_ip")->nullable(true)->change();
            $table->integer("is_logged_in")->nullable(true)->change();
            $table->integer("online_status")->nullable(true)->change();
            $table->string("fixed_header")->nullable(true)->change();
            $table->string("compact_sidebar")->nullable(true)->change();
            $table->string("boxed_wrapper")->nullable(true)->change();
            $table->string("leave_categories")->nullable(true)->change();
            $table->integer("ethnicity_type")->nullable(true)->change();
            $table->integer("nationality_id")->nullable(true)->change();
            $table->integer("citizenship_id")->nullable(true)->change();
        });
    }
}
