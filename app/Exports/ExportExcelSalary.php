<?php

namespace App\Exports;

use App\Repositories\AttendanceTimeRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use \App\Repositories\SalaryManagerRepository;
use App\Models\SalaryPayslip;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use App\Repositories\AttendanceDailyRepository;
use App\Models\AttendanceDaily;

class ExportExcelSalary implements FromCollection,WithStyles,WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $company_id;
    private $employee_id;
    private $month_salary;
//    private $payslip_manager;

    public function __construct($compnay_id, $employee_id, $month_salary){
        $this->company_id = $compnay_id;
        $this->employee_id = $employee_id;
        $this->month_salary = $month_salary;

    }
//    const WAYSTYPE
    public function collection()
    {
        $query = SalaryPayslip::with(['employeeSalary','bankAccount','department']);
            if (!empty($this->company_id)){
                $query->where('company_id', $this->company_id);
            }
            if ($this->employee_id !== null){
                $query->where('employee_id', $this->employee_id);
            }
            if ($this->month_salary != null){
                $query->where('salary_month', $this->month_salary);
            }
        $salary_arr = [];
        foreach ($query->get() as $key => $salary_id){
            $salary_arr[] = [
                'user_id' => $salary_id->employee_id,
                'fullname' => $salary_id->employeeSalary->last_name.' '.$salary_id->employeeSalary->first_name,
                'employee_id' => $salary_id->employeeSalary->employee_id,
                'total_working_days' => $salary_id->total_working_days,
                'total_all_late_month' => $salary_id->total_all_late_month. 'Phút',
                'total_all_leave_month' => $salary_id->total_all_leave_month. 'Phút',
            ];
        }
    //    dd($query->toSql(), $this->month_salary, $this->employee_id, $this->company_id);
        return collect($salary_arr);
    }
//    public function headings(): array {
//        return [
//            'Tên nhân viên',
//            'Mã nhân sự',
//            'Ngày công',
//            'Thời gian đi muộn',
//            'Thời gian về sớm',
//        ];
//    }
    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A5', 'Tên nhân viên');
        $sheet->setCellValue('B5', 'Mã nhân sự');
        $sheet->setCellValue('C5', 'Ngày công');
        $sheet->setCellValue('D5', 'Thời gian đi muộn');
        $sheet->setCellValue('E5', 'Thời gian về sớm');
        $colors = array(
            'X1' => 'E7F7FF',
            'X2' => 'E7F7FF',
            'N1' => 'E7B8FF',
            'N2' => 'E7B8FF',
            'L1' => 'E7F7C0',
            'L2' => 'E7F7C0',
            'V' => 'E5B3AE',
            'P' => '34A853',
            'P/2' => '34A853',
            'CN' => 'F3CDA1',
            '--' => 'FFFFF',
            '' => 'E7F7FF',
        );
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->setCellValue('F2', 'Chính thức')
              ->setCellValue('F3', 'Thử việc')
              ->setCellValue('G2', '1')
              ->setCellValue('G3', '2')
              ->setCellValue('K2', 'BẢNG KÝ HIỆU CÁC LOẠI NGÀY CÔNG')
              ->setCellValue('H3', 'Loại')
                ->setCellValue('I3', 'Đủ công')
                ->setCellValue('J3', 'Nửa công')
                ->setCellValue('K3', 'Lễ tết')
                ->setCellValue('L3', 'Vắng')
                ->setCellValue('M3', 'Nghỉ')
                ->setCellValue('N3', 'Chủ nhật')
                ->setCellValue('H4', 'Ký hiệu')
                ->setCellValue('I4', 'X1 - X2')
                ->setCellValue('J4', 'N1 - N2')
                ->setCellValue('K4', 'L1 - L2')
                ->setCellValue('L4', 'V')
                ->setCellValue('M4', 'P')
                ->setCellValue('N4', 'CN');

        $sheet->getStyle('I4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['X1']);
        $sheet->getStyle('J4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['N1']);
        $sheet->getStyle('K4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['L1']);
        $sheet->getStyle('L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['V']);
        $sheet->getStyle('M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['P']);
        $sheet->getStyle('N4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$colors['CN']);
        //
//        $sheet->getColumnDimension('A');
        $sheet->getColumnDimension('A')->setWidth(17);
        $sheet->getColumnDimension('B')->setWidth(17);
        $sheet->getColumnDimension('C')->setWidth(17);
        $sheet->getColumnDimension('D')->setWidth(17);
        $sheet->getColumnDimension('E')->setWidth(17);

        $dateInMonth = Carbon::createFromDate($this->month_salary);
        $formartDateDaily = $dateInMonth->format('Y-m-d');
        $columnArr = array('F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO');
        $last_col = 'E';

        $year = $dateInMonth->format('Y');
        $month = $dateInMonth->format('m');

        $startDateMonth = $dateInMonth->startOfMonth()->format("Y-m-d");
        $endDateMonth = $dateInMonth->endOfMonth()->format("Y-m-d");

        $date_arr = [];
        $dataPayslip = [];
        $payslip = $this->collection();

        foreach ($payslip as $payslipID){
            for($i = 1; $i <= $dateInMonth->daysInMonth; $i++){
                $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                $attendance_date = $year.'-'.$month.'-'.$i;
                $date_ = Carbon::createFromDate($attendance_date);
                $daily =  app()->make(AttendanceDaily::class)
                    ->where('employee_id', $payslipID['user_id'])
                    ->where('day', $attendance_date)
                    ->first();

                if ($daily == null){
                    if ($date_->format('l')=='Sunday') {
                        $status = 'CN';
                    }else{
                        $status = '--';
                    }
                }else{
                    $type = $daily->wages_type != 1 ? '1' : '2';
                    if ($daily->is_holiday == 1){
                        $status = 'L'.$type;
                    }else if ($date_->format('l')=='Sunday') {
                        $status = 'CN';
                    }elseif ($daily->check_in_at == null && $daily->check_out_at == null){
                        $status = 'V';
                    }else if ($daily->is_half_attendance == 1){
                        $status = 'N'.$type;
                    }else $status = 'X'.$type;
                }

                $dataPayslip[] = [
                    'check_in' => $daily->check_in_at ?? null,
                    'check_out' => $daily->check_in_at ?? null,
                    'employee_id' => $payslipID['user_id'],
                    'status' => $status,
                    'day' => $attendance_date,
                ];
            }
        }
        $rows = 6;
        $keyRows = 0;

        for($i = 1; $i <= $dateInMonth->daysInMonth; $i++){
            $i = str_pad($i, 2, 0, STR_PAD_LEFT);
            $attendance_date = $year.'-'.$month.'-'.$i;
            $date_arr[] = $attendance_date;
            $m = Carbon::createFromDate($attendance_date);
            $sheet->setCellValue($columnArr[$i - 1].'5', $m->format('l, d'));
            $sheet->getColumnDimension($columnArr[$i - 1])->setWidth(17);
        }
        foreach ($payslip as $keys => $daily){
            $sheet->setCellValue('A' . $rows, $daily['fullname']);
            $sheet->setCellValue('B' . $rows, $daily['employee_id']);
            $sheet->setCellValue('C' . $rows, $daily['total_working_days']);
            $sheet->setCellValue('D' . $rows, $daily['total_all_late_month']);
            $sheet->setCellValue('E' . $rows, $daily['total_all_leave_month']);
            $startCol = 6;
            $startRow = 6;
            foreach ($dataPayslip as $show_){
//                dd($daily['user_id'], $show_['employee_id']);
                if ($daily['user_id'] == $show_['employee_id']){
                    $sheet->setCellValue( $columnArr[$keyRows] . $rows, $show_['status']);
                    $cell_color = $colors[$show_['status']];
                    $sheet->getStyle($columnArr[$keyRows] . $rows)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF'.$cell_color); // fill background
                    $sheet->getStyle($columnArr[$keyRows] . $rows)->applyFromArray($styleArray);// fill boder
                    $sheet->getStyle($columnArr[$keyRows] . $rows)->getAlignment()->setHorizontal('center'); // text center
                    $startCol++;
                    $last_col = $columnArr[$keyRows];
                    $keyRows++;
                }
            }
            $startRow++;
            $keyRows = 0;
            $rows++;
        }
        foreach (range('A','E') as $val) {$sheet->getColumnDimension($val)->setAutoSize(true);} // set width auto
        $sheet->getRowDimension('5')->setRowHeight(40); // set height
        $sheet->getStyle('B1:'.$last_col.$rows)->getAlignment()->setHorizontal('center');
    }
    public function startCell(): string
    {
        return 'A5';
    }
//    public function map($payslip): array {
//
//
//        return $salary_arr;
//    }
}
