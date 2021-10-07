<?php
namespace App\Repositories;

use App\Models\BusinessSetting;

class BusinessSettingRepository extends Repository
{
    public function getModel(): string
    {
        return BusinessSetting::class;
    }

    public function getBusinessOptions($businessId) {
        return $this->model->whereBusinessId($businessId)->get();
    }

    public function setBusinessOption($businessId, $option, $value) {
        $record = $this->model->where([
            'business_id' => $businessId,
            'option' => $option
        ])->first();
        if ($record) {
            $record->update(['value' => $value]);
        } else {
            # set type first => it will affect with value;
            $record = new BusinessSetting([
                'business_id' => $businessId,
                'option' => $option,
                'type'   => 'json'
            ]);
            $record->value = $value;
            $record->save();
        }
    }

}
