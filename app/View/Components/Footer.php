<?php

namespace App\View\Components;

use App\Classes\Settings\SettingManager;
use Illuminate\View\Component;

class Footer extends Component
{
    public $date;
    public $text;
    public $isShowDate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $settings = SettingManager::getInstance();
        $this->isShowDate = $settings->isEnableCurrentYear();
        $this->text = $settings->get('footer_text');
        if ($this->isShowDate) {
            $this->date = date("Y-m-d");
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.footer');
    }
}
