<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Classes\Settings\SettingManager as Settings;

class CompanyName extends Component
{

    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = Settings::getCompanyName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.company-name');
    }
}
