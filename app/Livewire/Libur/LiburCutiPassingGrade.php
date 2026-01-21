<?php

namespace App\Livewire\Libur;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\MasterOrganizationRepo;
use Livewire\Component;

class LiburCutiPassingGrade extends Component
{
    public $dt = [];

    public function getOrgBulanan()
    {
        $this->dt['org'] = MasterOrganizationRepo::allOrg()->toArray();
    }

    public function mount()
    {
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->getOrgBulanan();
        $this->dt['month'] = request()->query('month', now()->month);
        $this->dt['year']  = request()->query('year', now()->year);
        $this->dt['report_print_id'] = [1,5,9];
    }

    protected $masterOrganizationRepo;
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
    ){
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_cuti_passing_grade');
    }
}
