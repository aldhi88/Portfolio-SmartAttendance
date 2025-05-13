<?php

namespace App\Livewire\Report;

use Livewire\Component;

class ReportAbsen extends Component
{
    public $pass;
    public function render()
    {
        return view('report.report_absen');
    }
}
