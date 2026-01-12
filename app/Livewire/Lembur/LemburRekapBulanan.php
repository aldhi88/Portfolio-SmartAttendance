<?php

namespace App\Livewire\Lembur;

use App\Helpers\PublicHelper;
use App\Repositories\MasterOrganizationRepo;
use Livewire\Component;

class LemburRekapBulanan extends Component
{
    public $dt = [];

    public function getOrgBulanan()
    {
        $this->dt['org'] = MasterOrganizationRepo::allOrg()->toArray();

    }

    public function mount()
    {
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->getOrgBulanan();
        $this->dt['month'] = request()->query('month', now()->month);
        $this->dt['year']  = request()->query('year', now()->year);
        $this->dt['report_print_id'] = [1,5,9];
    }

    public $pass;
    public function render()
    {
        return view('lembur.lembur_rekap_bulanan');
    }
}
