<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Daterangepicker extends Component
{
    public $id;
    public $name;
    public $delimiter;
    public $maxDate;
    public $minDate;
    // public $defaultStart;
    // public $defaultEnd;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $name, $delimiter = '/', $minDate = null, $maxDate = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->delimiter = $delimiter;
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.date-range-picker');
    }
}
