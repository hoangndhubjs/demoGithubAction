<?php

namespace App\View\Components;

use App\Classes\Settings\SettingManager;
use Illuminate\View\Component;

class Logo extends Component
{

    public $logo;
    public $company_name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $default_logo = 'logo-light.png';
        if (config('layout.header.self.theme') === 'light') {
            $default_logo = 'logo-dark.png';
        } elseif (config('layout.header.self.theme') === 'dark') {
            $default_logo = 'logo-light.png';
        }
        $logo = SettingManager::getOption('logo', $default_logo);
        if ($logo) {
            $logo = url('storage/uploads/logo/logo/'.$logo);//Storage::url($logo);
        } else {
            $logo = $default_logo;
        }
        $this->logo = $logo;
        $this->company_name = SettingManager::getCompanyName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.logo');
    }
}
