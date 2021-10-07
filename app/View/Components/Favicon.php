<?php

namespace App\View\Components;

use App\Classes\Settings\SettingManager;
use Illuminate\View\Component;

class Favicon extends Component
{
    public $image;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->image = SettingManager::getOption('favicon');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.favicon');
    }
}
