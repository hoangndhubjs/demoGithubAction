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

class PayrollBank implements FromCollection,WithStyles,WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $request;


    public function __construct($request){
        $this->request = $request;

    }
//    const WAYSTYPE
    public function collection()
    {
        $date = Carbon::createFromFormat('m-Y', $this->request->date);
        $query = SalaryPayslip::with(['employeeSalary','bankAccount','department','employeeCompany']);


        if ($this->request->module == 'payroll'){
            if ($this->request->status != 'null'){
                $query->where('status', $this->request->status);
            }
            if ($this->request->department_id != 'null'){
                $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->status != 'null'){
                $query->where('status', $this->request->status);
            }else{
                $query->where('status', 2);
            }
        }else{
            if ($this->request->employee_id != 'null'){
                $query->where('employee_id', $this->request->employee_id);
            }
        }
        if ($this->request->company_id != 'null'){
            $query->where('company_id', $this->request->company_id);
        }
        if ($date != 'null'){
            $query->where('salary_month', $date->format("Y-m"));
        }
        $salary_arr = [];
        foreach ($query->get() as $key => $salary_id){
            $salary_month = Carbon::createFromFormat('m-Y', $this->request->date);
            $salary_arr[] = [
                'fullname' => $salary_id->employeeSalary->last_name.' '.$salary_id->employeeSalary->first_name,
                'name_company' => $salary_id->employeeCompany !== null ? $salary_id->employeeCompany->company_name : '',
                'bank' => $salary_id->bankAccount !== null ? $salary_id->bankAccount->account_number : '',
                'grand_net' => app('hrm')->getCurrencyConverter()->getUserFormat($salary_id->grand_net_salary),
                'total_statutory_deductions' => app('hrm')->getCurrencyConverter()->getUserFormat($salary_id->total_statutory_deductions),
                'salary_month' => $salary_month->format('m-Y'),
                'date_payroll' => $salary_id->year_to_date,
            ];
        }
//dd($salary_arr);
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
        $sheet->setCellValue('A1', 'Tên nhân viên');
        $sheet->setCellValue('B1', 'Công ty');
        $sheet->setCellValue('C1', 'Số tài khoản');
        $sheet->setCellValue('D1', 'Phải trả ròng');
        $sheet->setCellValue('E1', 'Tiền bảo hiểm');
        $sheet->setCellValue('F1', 'Tháng lương');
        $sheet->setCellValue('G1', 'Ngày biên chế');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
    }
    public function startCell(): string
    {
        return 'A2';
    }
//    public function map($payslip): array {
//
//
//        return $salary_arr;
//    }
}
