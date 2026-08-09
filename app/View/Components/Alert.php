<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $title;
    public $messages;
    public $display;

    public function __construct($type = '', $title = '', $messages = [], $display = '')
    {
        $this->type = $type;
        $this->title = $title;
        $this->messages = $messages;
        $this->display = $display;
    }

    public function render()
    {
        return view('components.alert');
    }
}
