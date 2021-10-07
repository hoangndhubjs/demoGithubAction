<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\Services\GoogleAPI;
use App\Models\AttendanceDaily;
use App\Models\Employee;
use App\Models\Info_CC;
use App\Models\SalaryPaychecks;
use App\Repositories\AttendanceTimeRequestRepository;
use App\Repositories\EmployeeTmpPayslipRepository;
use Carbon\Carbon;
use DateTime;
use Google_Service_Exception;
use Google_Service_Sheets;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayrollGoogleSheetController extends Controller
{

    private $googleAPI;
    private $employees_tmp_payslip;
    private $attendace_time_request;

    public function __construct(
        GoogleAPI $googleAPI,
        EmployeeTmpPayslipRepository $employees_tmp_payslip,
        AttendanceTimeRequestRepository $attendace_time_request
    )
    {
        $this->googleAPI = $googleAPI;
        $this->employees_tmp_payslip = $employees_tmp_payslip;
        $this->attendace_time_request = $attendace_time_request;
    }

    const RANGE_NAME = 'TL';
    const RANGE_START = 'I';
    const RANGE_END = 'AM';

    public function exportDataHrmToGoogleSheet(Request $request)
    {
        $date = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');
        $client = $this->googleAPI->getGoogleClient();
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = env('SHEET_ID_CHECK_IN_OUT');

        // thong tin ma nhan vien va stt duoc lay tu sheet ve
        foreach ($this->getInfoEmployee($date) as $inf) {
            $range = self::RANGE_NAME . '!' . self::RANGE_START . $inf->stt . ':' . self::RANGE_END . $inf->stt; // range chấm công
            $rangeComeLate = self::RANGE_NAME . '!CI' . $inf->stt . ':CI'; // range số phút đi muộn
            $rangeOT_Official = self::RANGE_NAME . '!CO' . $inf->stt; // range OT chính thức
            $rangeOT_Trail_Work = self::RANGE_NAME . '!CQ' . $inf->stt; // range OT thử việc
            $user_id = Employee::where('employee_id', $inf->employee_id)->first();

            if (isset($user_id['user_id'])) {
                $time = $this->fullcalendarByMonth($user_id['user_id'], $date);
            }

            $data = [];
            $come_late = [];
            $time_OT_Official = [];
            $time_OT_Trail_Work = [];
            foreach ($time as $v) {
                if ($inf->employee_id == $v['employee_id']) {
                    $data[] = $v['date'];
                    $come_late[] = [(int)$v['come_late']];
                    $time_OT_Official[] = [(int)$inf->OT_official];
                    $time_OT_Trail_Work[] = [(int)$inf->OT_trail_work];
                }
            }

            $requestBody = new \Google_Service_Sheets_ValueRange([
                'values' => $data
            ]);

            $requestBodyComeLate = new \Google_Service_Sheets_ValueRange([
                'values' => $come_late
            ]);

            $requestBodyTimeOtOfficial = new \Google_Service_Sheets_ValueRange([
                'values' => $time_OT_Official
            ]);
            $requestBodyTimeOtTrailWork = new \Google_Service_Sheets_ValueRange([
                'values' => $time_OT_Trail_Work
            ]);


            $params = [
                'valueInputOption' => 'RAW'
            ];

            try {
                // update dữ liệu ở sheet
                $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
                $service->spreadsheets_values->update($spreadsheetId, $rangeComeLate, $requestBodyComeLate, $params);
                $service->spreadsheets_values->update($spreadsheetId, $rangeOT_Official, $requestBodyTimeOtOfficial, $params);
                $service->spreadsheets_values->update($spreadsheetId, $rangeOT_Trail_Work, $requestBodyTimeOtTrailWork, $params);

                //update trạng thái đã được export ở sheet
                Info_CC::where('employee_id', $inf->employee_id)->where('stt', $inf->stt)->where('month', $date)->update(['status' => 1]);

                Log::debug('update sheet 1 data success');
            } catch (Google_Service_Exception $e) {
                Log::debug($e);
            }


            sleep(1);
        }

    }

    //tinh toan cham cong thang
    public function fullcalendarByMonth($user_id, $getDate)
    {

        $date = $getDate . '-01';
        $imonth_year = explode('-', $getDate);
        $day = date('d', strtotime($date));
        $month = date($imonth_year[1], strtotime($date));
        $year = date($imonth_year[0], strtotime($date));
        $month_year = $getDate;

        // total days in month
        $date = DateTime::createFromFormat('Y-m', $getDate);
        $daysInMonth = $date->format('t');

        $employeeIsActive = Employee::where('user_id', $user_id)->get();

        $payslip = $this->employees_tmp_payslip->getInformationTimeSheetByMonth($getDate, $employeeIsActive);

        $dataPayslip = [];
        foreach ($payslip as $key => $payslipID) {

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                $attendance_date = $year . '-' . $month . '-' . $i;
                $date_ = Carbon::createFromDate($attendance_date);
                $daily = app()->make(AttendanceDaily::class)
                    ->with(['employee.office_shift'])
                    ->where('employee_id', $payslipID['id'])
                    ->where('day', $attendance_date)
                    ->first();

                $status = '';
                if ($daily == null) {
                    if ($date_->englishDayOfWeek === 'Sunday') {
                        $status = 'CN';
                    } else {
                        $status = '--';
                    }
                } else {
                    $type = $daily->wages_type == 1 ? '1' : '2'; //nho kiem tra lai phan nay
                    if (isset($daily->is_go_on_business) && $daily->is_go_on_business == 1) {
                        $status = 'C' . $type;
                    } else {
                        $office_shift = $daily->employee->office_shift ?? null;
                        $sunday_in_time = $office_shift->sunday_in_time ?? null;

                        if ($daily->is_holiday == 1) {
                            $status = 'L' . $type;
                        } else if ($daily->is_online == 1) {
                            $status = 'O' . $type;
                        } else if ($daily->is_half_attendance == 1) {
                            $status = 'N' . $type;
                        } else if ($daily->total_request_leave_full == 1) {
                            $status = 'P';
                        } else if ($daily->attendance_count == 1) {
                            $status = 'X' . $type;
                        } else if ($date_->englishDayOfWeek === 'Sunday' && $sunday_in_time != "") {
                            //Thang phai lam chu nhat
                            if ($daily->is_holiday == 1) {
                                $status = 'L' . $type;
                            } else if ($daily->is_online == 1) {
                                $status = 'O' . $type;
                            } else if ($daily->is_half_attendance == 1) {
                                $status = 'N' . $type;
                            } else if ($daily->total_request_leave_full == 1) {
                                $status = 'P';
                            } else if ($daily->attendance_count == 1) {
                                $status = 'X' . $type;
                            } else if ($daily->attendance_count == 0 || ($daily->check_in_at == null && $daily->check_out_at == null)) {
                                $status = 'K';
                            }
                        } else if ($date_->englishDayOfWeek === 'Sunday' && $sunday_in_time == "") {
                            //Thang ko phai lam chu nhat;
                            $status = "CN";
                        } else if ($daily->attendance_count == 0 || ($daily->check_in_at == null && $daily->check_out_at == null)) {
                            $status = 'K';
                        }
                    }
                }

                $dataPayslip[] = [
                    'resourceId' => $payslipID['id'],
                    'title' => $status,
                    'start' => $attendance_date,
                    'end' => $attendance_date,
                    'className' => $status == "P/2" ? "P_2" : $status
                ];

            }

            sleep(1);
        }

        $data = [];
        foreach ($payslip as $key => $user) {

            foreach ($dataPayslip as $value) {

                if ($user['id'] == $value['resourceId']) {
                    $monthData[] = $value['title'];
                }
            }

            $data[] = array(
                'id' => $user['id'],
                'employee_name' => $user['employee_name'],
                'employee_id' => $user['employee_id'],
                'come_late' => $user['come_late'],
                'date' => $monthData
            );

            unset($monthData);
            sleep(1);
        }

        return $data;
    }

    // read data from google sheet: mã nhân viên, stt rows
    public function readData(Request $request)
    {
        $date = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');
        $client = $this->googleAPI->getGoogleClient();
        $service = new Google_Service_Sheets($client);

        $spreadsheetId = env('SHEET_ID_CHECK_IN_OUT');
        $range = self::RANGE_NAME . '!B12:B';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        // thông tin nhân sự được lấy từ google sheet
        $values = $response->getValues();

        // thông tin danh sách làm thêm giờ được lấy từ database


        $stt_row = 12;

        if (empty($values)) {
            print "No data found.\n";
        } else {
            try {
                foreach ($values as $key => $row) {
                    $totalTimeRequest = $this->attendace_time_request->getTimeRequestMonth($request, $row[0]);

                    $OT_trail_work = !empty($totalTimeRequest) ? str_replace(".", ",", $totalTimeRequest[0]['total_hour_tw_user']) : 0;
                    $OT_official = !empty($totalTimeRequest) ? str_replace(".", ",", $totalTimeRequest[0]['total_hour_user']) : 0;

                    $st = $stt_row + $key;
                    $already_exit = Info_CC::where('employee_id', $row[0])->where('stt', $st)->where('month', $date)->first();
                    if ($already_exit) {
                        $already_exit->update([
                            'employee_id' => $row[0],
                            'stt' => $st,
                            'month' => $date,
                            'OT_trail_work' => $OT_trail_work,
                            'OT_official' => $OT_official
                        ]);
                    } else {
                        Info_CC::create([
                            'employee_id' => $row[0],
                            'stt' => $st,
                            'month' => $date,
                            'OT_trail_work' => $OT_trail_work,
                            'OT_official' => $OT_official
                        ]);
                    }

                }
            } catch (ModelNotFoundException $exception) {
                Log::debug('Error: Get data form Google Sheet');
            }
        }
    }

    // lấy tất cả thông tin nhân viên theo tháng và chưa được ghi vào sheet
    public function getInfoEmployee($month)
    {
        $info = Info_CC::where('month', $month)->where('status', 2)->get();

        return $info;
    }

    public function clearDataFromGoogleSheet(Request $request)
    {
        $date = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');

        try {
            Info_CC::where('month', $date)->delete();

            return $this->responseSuccess('Xóa thông tin thành công.');

        } catch (\Exception $exception) {
            Log::debug('Remove data error');
        }

    }

    // lấy tất cả thông tin lương, công... từ google sheet về db
    public function getAllDataSheet(Request $request)
    {
        $date = $request->month ? date('Y-m', strtotime('01-' . $request->month)) : date('Y-m');

        $client = $this->googleAPI->getGoogleClient();
        $service = new Google_Service_Sheets($client);

        $spreadsheetId = env('SHEET_ID_CHECK_IN_OUT');
        $range = self::RANGE_NAME . '!A12:CV';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        // thông tin nhân sự được lấy từ google sheet
        $values = $response->getValues();

        if (empty($values)) {
            print "No data found.\n";
        } else {
            foreach ($values as $key => $row) {
                if (!empty($row[1])) {
                    $employee_id = $row[1]; // mã nhân viên
                    $department_grade = $row[2]; // khối
                    $paid_leave = $row[59]; //phép tồn
                    $basic_salary = $this->replaceDataSheet($row[62]); // Lương Chấm Công(Chính Thức)
                    $salary_perday = $this->replaceDataSheet($row[69]);// Lương 1 ngày công chính thức
                    $trail_salary = $this->replaceDataSheet($row[63]);// Lương Chấm Công(Thử Việc/Học Việc/Part Time)
                    $salary_perday_trail = $this->replaceDataSheet($row[70]);// Lương 1 ngày công thử việc/học việc/part time
                    $net_salary = $this->replaceDataSheet($row[61]);// Lương khoán
                    $total_all_work_month = $row[64]; // số ngày được tính đủ công trong tháng
                    $total_leave = $row[55]; // Số ngày nghỉ không lương trong tháng
                    $total_formal_working_days = $row[56]; // Tổng công trong tháng (Chính Thức) (Số Lượng)
                    $salary_basic = $this->replaceDataSheet($row[71]); // Tổng công trong tháng (Chính Thức) (Thành tiền)
                    $total_trial_working_days = $row[57]; //Tổng công trong tháng (Thử việc/HV/PT) (Số lượng)
                    $salary_trail = $this->replaceDataSheet($row[72]); //Tổng công trong tháng (Thử việc/HV/PT) (Thành tiền)
                    $allowance_days = $row[56]; // Tổng công đi làm nhận được phụ cấp (Số lượng)
                    $allowance_days_salary = $this->replaceDataSheet($row[73]); // Tổng công đi làm nhận được phụ cấp (Thành tiền)
                    $salary_liability = $this->replaceDataSheet($row[74]); // Tiền phụ cấp trách nhiệm
                    $total_all_kpi_month = $this->replaceDataSheet($row[75]); //Lương hiệu suất công việc
                    $total_all_bonus_month = $this->replaceDataSheet($row[76]); //Thưởng nóng
                    $allowance_sum_amount = $this->replaceDataSheet($row[77]); // Các khoản cộng khác
                    $total_statutory_deductions = $this->replaceDataSheet($row[78]); // Tiền bảo hiểm
                    $saudi_gosi_amount = $this->replaceDataSheet($row[79]); // Tiền thuế thu nhập cá nhân
                    $union_money = $this->replaceDataSheet($row[80]); // Phí công đoàn
                    $total_advance = $this->replaceDataSheet($row[81]); // Tiền lương đã ứng
                    $monetary_fine = $this->replaceDataSheet($row[82]); // Các Khoản Phạt & Khấu Trừ Vào Quỹ Team
                    $total_price_datcom = $this->replaceDataSheet($row[83]); // Tiền ăn
                    $minus_money = $this->replaceDataSheet($row[84]); // Các khoản trừ khác
                    $total_overtime_formal = $row[92]; // Số giờ OT chính thức (đã tính hệ số) (Số lượng)
                    $overtime_formal_salary = $this->replaceDataSheet($row[93]); // Số giờ OT chính thức (đã tính hệ số) (Thành tiền)
                    $total_overtime_trial = $row[94]; // Số Giờ OT Thử Việc/HV/PT (đã tính hệ số) (Số lượng)
                    $overtime_trail_salary = $this->replaceDataSheet($row[95]); // Số Giờ OT Thử Việc/HV/PT (đã tính hệ số) (Thành tiền)
                    $sum_overtime_formal_trail = (int)$total_overtime_formal + (int)$total_overtime_trial; // Tổng(Over Time)
                    $sum_overtime_formal_trail_salary = (int)$overtime_formal_salary + (int)$overtime_trail_salary; // Tổng(Over Time)
                    $diligence_salary = $row[65]; // Bạn có bị tính chuyên cần không ?
                    $total_all_late_month = $row[86]; // Trừ tiền do số Phút Đi Muộn (Số Lượng)
                    $total_all_late_month_salary = $this->replaceDataSheet($row[88]); // Trừ tiền do số Phút Đi Muộn (Thành tiền)
                    $total_leave_salary = $this->replaceDataSheet($row[89]); //Trừ tiền do số ngày nghỉ không lương (Thành tiền)
                    $total_attendances = $this->replaceDataSheet($row[91]); //Tiền chuyên cần nhận được
                    $note = isset($row[97]) ? $row[97] : ''; //Ghi chú
                    $grand_net_salary = $this->replaceDataSheet($row[96]); // Số Tiền Thực Nhận
                    $insurance_money = isset($row[98]) ? $row[98] : 0;
                    $insurance_salary = $this->replaceDataSheet($insurance_money); // Tiền bảo hiểm công ty đóng cho nhân viên
                    $salary_costs = isset($row[99]) ? $row[99] : 0;
                    $total_grand_net_salary = $this->replaceDataSheet($salary_costs); // Tổng Chi Phí Lương trong tháng dành cho NV

                    $data = [
                        'employee_id' => $employee_id,
                        'department_grade' => $department_grade,
                        'paid_leave' => $paid_leave,
                        'basic_salary' => $basic_salary,
                        'salary_perday' => $salary_perday,
                        'trail_salary' => $trail_salary,
                        'salary_perday_trail' => $salary_perday_trail,
                        'net_salary' => $net_salary,
                        'total_all_work_month' => $total_all_work_month,
                        'total_leave' => $total_leave,
                        'total_formal_working_days' => $total_formal_working_days,
                        'salary_basic' => $salary_basic,
                        'total_trial_working_days' => $total_trial_working_days,
                        'salary_trail' => $salary_trail,
                        'allowance_days' => $allowance_days,
                        'allowance_days_salary' => $allowance_days_salary,
                        'salary_liability' => $salary_liability,
                        'total_all_kpi_month' => $total_all_kpi_month,
                        'total_all_bonus_month' => $total_all_bonus_month,
                        'allowance_sum_amount' => $allowance_sum_amount,
                        'total_statutory_deductions' => $total_statutory_deductions,
                        'saudi_gosi_amount' => $saudi_gosi_amount,
                        'union_money' => $union_money,
                        'total_advance' => $total_advance,
                        'monetary_fine' => $monetary_fine,
                        'total_price_datcom' => $total_price_datcom,
                        'minus_money' => $minus_money,
                        'total_overtime_formal' => $total_overtime_formal,
                        'overtime_formal_salary' => $overtime_formal_salary,
                        'total_overtime_trial' => $total_overtime_trial,
                        'overtime_trail_salary' => $overtime_trail_salary,
                        'sum_overtime_formal_trail' => $sum_overtime_formal_trail,
                        'sum_overtime_formal_trail_salary' => $sum_overtime_formal_trail_salary,
                        'diligence_salary' => $diligence_salary,
                        'total_all_late_month' => $total_all_late_month,
                        'total_all_late_month_salary' => $total_all_late_month_salary,
                        'total_leave_salary' => $total_leave_salary,
                        'total_attendances' => $total_attendances,
                        'note' => $note,
                        'grand_net_salary' => $grand_net_salary,
                        'insurance_salary' => $insurance_salary,
                        'total_grand_net_salary' => $total_grand_net_salary,
                        'month' => $date,
                    ];

                    try {
                        $employee = Employee::where('employee_id', $row[1])->first();
                        if (isset($employee)) {
                            $already_exit = SalaryPaychecks::where('employee_id', $row[1])->where('month', $date)->first();
                            $data['department_id'] = $employee->department_id ? $employee->department_id : 0;
                            if ($already_exit) {
                                $already_exit->update($data);
                            } else {
                                SalaryPaychecks::create($data);
                            }
                        }
                    } catch (ModelNotFoundException $exception) {
                        Log::debug('import data payroll error');
                    }
                }
            }
        }
    }

    public function replaceDataSheet($string)
    {
        $str = str_replace('.', '', $string); // cắt bỏ dấu chấm

        $number = preg_replace('/[^0-9.]+/', '', $str); // chỉ lấy số, bỏ hết text

        return trim($number);
    }
}
