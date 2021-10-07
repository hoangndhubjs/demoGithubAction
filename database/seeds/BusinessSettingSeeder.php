<?php

use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'business_id' => 1,
                'option' =>  "maximum_late_time",
                'value' => "60",
                'type' => "int"
            ],
            [
                'business_id' => 1,
                'option' =>  "maximum_early_leave",
                'value' => "60",
                'type' => "int"
            ],
            [
                'business_id' => 1,
                'option' =>  "lunch_rest_start",
                'value' => "12:00",
                'type' => "hour"
            ],
            [
                'business_id' => 1,
                'option' =>  "lunch_rest_end",
                'value' => "13:30",
                'type' => "hour"
            ],
            [
                'business_id' => 1,
                'option' =>  "minimum_order_price",
                'value' => "25000",
                'type' => "int"
            ],
            [
                'business_id' => 1,
                'option' =>  "maximum_late_time_allowed",
                'value' => "300",
                'type' => "int"
            ],
            [
                'business_id' => 1,
                'option' =>  "ignore_pickup_meal_delivery",
                'value' => '["00001","00002","00004","00024","00073","00080","00083","00077","00081","00087","00078"]',
                'type' => "json"
            ],
            [
                'business_id' => 1,
                'option' =>  "employees_can_set_menu_dishes",
                'value' => '["00073","00078","00024"]',
                'type' => "json"
            ],
            [
                'business_id' => 1,
                'option' =>  "employees_full_allowance",
                'value' => '["165","125"]',
                'type' => "json"
            ]
        ];
        BusinessSetting::insertOrIgnore($data);
    }
}
