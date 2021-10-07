<?php

namespace App\Exports;

use App\Classes\Settings\SettingManager;
use App\Models\MealOrder;
use App\Models\FoodMenu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Repositories\FoodRepository;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class ExportOrderRice implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithStyles, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $today = date('Y-m-d');
        $type = $this->_getPeriod();

        $query = MealOrder::where('create_date', $today)->where('type', $type)->get();

        if (!$query->toArray()){
            echo "Chưa có đơn nào để xuất excel";
            die;
        }
        
        foreach($query as $item) {
            $item->mon_chinh = @json_decode($item->mon_chinh);
            $item->mon_phu = @json_decode($item->mon_phu);
            $item->mon_rau = @json_decode($item->mon_rau);
            $food_ids = array_merge($item->mon_chinh, $item->mon_phu, $item->mon_rau);
            $foods = (new FoodRepository())->getFoodsByIds($food_ids)->keyBy('id');
            $item->foods = $foods;
        }
       
        $stt = 1;
        foreach ($query as $row) {
            foreach($row->mon_chinh as $mc) {
                $result_mc[] = $row->foods[$mc]->title;
            }
            foreach($row->mon_phu as $mp) {
                $result_mp[] = $row->foods[$mp]->title;
            }
            foreach($row->mon_rau as $mr) {
                $result_mr[] = $row->foods[$mr]->title;
            }
            $mon_chinh = implode('',$result_mc);
            $mon_phu = implode('',$result_mp);
            $mon_rau = implode('',$result_mr);
            $result[] = array(
                '0' => $stt++,
                '1' => $row->employee->last_name . " " . $row->employee->first_name,
                '2' => $row->employee->employee_id,
                '3' => $mon_chinh,
                '4' => $mon_phu,
                '5' => $mon_rau,
                '6' => number_format($row->price) . " VNĐ"
            );
            unset($result_mc);
			unset($result_mp);
            unset($result_mr);
        }
        if($result) {
            MealOrder::where('status', '=', 0)->update(array('status' => 1));
            FoodMenu::where('status', '=', 0)->update(array('status' => 1));
        }
        return (collect($result));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên nhân viên',
            'Mã nhân viên',
            'Món chính',
            'Món phụ',
            'Món rau',
            'Giá tiền',
        ];
    }

    protected function _getPeriod() {
        $lunch_confirmed_hour = config('constants.ENDING_OF_MORNING');
        return time() < strtotime($lunch_confirmed_hour) ? MealOrder::TYPE_LUNCH : MealOrder::TYPE_DINNER;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('J1:L1');
        $sheet->setCellValue('J1', 'Người đi lấy cơm');
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'Danh sách đặt cơm');
        $sheet->setCellValue('J2', 'STT');
        $sheet->setCellValue('K2', 'Mã nhân viên');
        $sheet->setCellValue('L2', 'Tên nhân viên');
        
        $random = $this->collection()->toArray();
        shuffle($random);
        $stt = 1;
        $currentRandom = 3;
        $keyRan = [];
        $boss = SettingManager::getOption('ignore_pickup_meal_delivery');
        foreach ($random as $key => $item) {
            if(count($keyRan) == 3) {
                break;
            }
            if ($boss && in_array($item[2], $boss)) {
                continue;
            } else {
                $keyRan[$key] = $random;
            }
            $sheet->setCellValue('J' . $currentRandom, $stt++)
                  ->setCellValue('K' . $currentRandom, $item[2])
                  ->setCellValue('L' . $currentRandom, $item[1]);

            $currentRandom++;
        }
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $title = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $textCenter = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ];
                $event->sheet->getStyle('A1:L2')->applyFromArray($title);
                $event->sheet->getStyle('A3:A300')->applyFromArray($textCenter);
                $event->sheet->getStyle('C3:C300')->applyFromArray($textCenter);
                $event->sheet->getStyle('J3:J300')->applyFromArray($textCenter);
                $event->sheet->getStyle('K3:K300')->applyFromArray($textCenter);
                $event->sheet->getStyle('A1:L1')->getFont()->setSize(15);
                $event->sheet->getStyle('A2:L2')->getFont()->setSize(12);
                $event->sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6FA');
                $event->sheet->getStyle('J1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA800');
                $event->sheet->getColumnDimension('D')->setAutoSize(false);
                $event->sheet->getColumnDimension('A')->setAutoSize(false);
                $event->sheet->getColumnDimension('A')->setWidth(8);
                $event->sheet->getColumnDimension('D')->setWidth(32);
                $event->sheet->getColumnDimension('K')->setWidth(15);
                $event->sheet->getColumnDimension('L')->setWidth(22);
                $event->sheet->getStyle('A1:G1')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('J1:L1')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }
}
