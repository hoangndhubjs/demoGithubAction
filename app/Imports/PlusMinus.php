<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\Shared\Date;
class PlusMinus implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $read_data = $collection->toArray();
        unset($read_data[0]);
        $data_plus = array();
        $data_minus = array();
        foreach ($read_data as $data){
            $employee_id = Employee::selectRaw('user_id')->where('employee_id', $data[2])->first();
            $data_plus['plus'][] = array(
                "allowance_title" => $data[3],
                "allowance_amount" => 1,
                "is_allowance_taxable" => 0,
                "amount_option" => intval($data[4]),
                "employee_id" => $employee_id->user_id,
                "year_month" => Carbon::createFromFormat('m-Y', $data[5])->format('Y-m')
            );
            $data_minus['minus'][] = array(
                "user_id" => $employee_id->user_id,
                "title" => $data[6],
                "amount_option" => $data[9],
                "money" => intval($data[7]),
                "year_month" => Carbon::createFromFormat('m-Y', $data[8])->format('Y-m')
            );
        }
        dd(array_merge($data_plus, $data_minus));
        return array_merge($data_plus, $data_minus);
    }
}
