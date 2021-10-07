<?php

namespace App\Http\Controllers\Admin\Payrolls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollGoogleSheetController extends Controller
{

    public function index(Request $request)
    {
        $page_title = __("export_data_checkin_out_and_payroll");
        $defaultDate = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');
        return view('admin.payroll.timekeepings_payroll', compact('page_title', 'defaultDate'));
    }


    public function payrollToSheet(Request $request)
    {
        $page_title = __("import_data_payroll");
        $defaultDate = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');
        return view('admin.payroll.payroll_to_sheet', compact('page_title', 'defaultDate'));
    }
}
