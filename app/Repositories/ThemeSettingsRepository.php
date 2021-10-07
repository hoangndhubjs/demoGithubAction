<?php
namespace App\Repositories;

use App\Models\ThemeSetting;

class ThemeSettingsRepository extends Repository
{
    public function getModel(): string
    {
        return ThemeSetting::class;
    }

    public function getThemeSettings($id) {

        $theme_setting = ThemeSetting::where('theme_settings_id', $id)->first();

        return $theme_setting;
    }
}
