<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Classes\Theme\Metronic;

class Icon extends Component
{
    public $type;
    public $icon;
    public $class;
    public $category;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $icon, $category = false, $class = '')
    {
        $this->type = $type;
        $this->icon = $icon;
        $this->category = $category;
        $this->class = $class;
    }

    public function getIconClass() {
        $classes = [
            'ki' => 'ki ki-%s',
            'fa' => 'fa fa-%s',
            'flaticon' => 'flaticon-%s',
            'flaticon2' => 'flaticon2-%s',
            'socicon' => 'socicon-%s',
        ];
        $class = sprintf($classes[$this->type] ?? '%s', $this->icon);
        $class .= " ".$this->class;
        return $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $content = '';
        if ($this->type === 'svg') {
            $content = Metronic::getSVG(sprintf('media/svg/icons/%s/%s.svg', $this->category, $this->icon), $this->class);
        } else {
            $content = $this->getIconClass();
        }
        return view('components.icon', compact('content'));
    }
}
