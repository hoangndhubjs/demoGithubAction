<?php
namespace App\Repositories;

use App\Models\FileManagerSettings;

class FileManagerSettingRepository extends Repository
{
    public function getModel(): string
    {
        return FileManagerSettings::class;
    }

    public function getFileManagerSetting($id) {
        $file_setting = FileManagerSettings::where('setting_id', $id)->first();

        return $file_setting;
    }
}
