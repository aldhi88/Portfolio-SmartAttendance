<?php

namespace App\Livewire\LemburVendor;

use App\Helpers\PublicHelper;
use Livewire\Component;

class LemburVendorRekapBulanan extends Component
{
    public $dt = [];
    public function mount()
    {
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->dt['month'] = request()->query('month', now()->month);
        $this->dt['year']  = request()->query('year', now()->year);
        $this->dt['report_print_id'] = [1,2,3,5,9,11];
    }

    public $pass;
    public function render()
    {
        return view('lembur_vendor.lembur_rekap_bulanan');
    }
}
