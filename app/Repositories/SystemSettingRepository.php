<?php
namespace App\Repositories;

use App\Models\SystemSetting;

class SystemSettingRepository extends Repository
{
    public function getModel(): string
    {
        return SystemSetting::class;
    }

    public function getSetting() {
        return SystemSetting::where('setting_id', 1)->first();
    }

    public function getSettingFromId($id) {
        return SystemSetting::where('setting_id', $id)->first();
    }
}
