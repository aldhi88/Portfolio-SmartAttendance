<?php

namespace App\Livewire\LemburVendor;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use Livewire\Component;

class LemburVendorData extends Component
{
    protected $dataLemburRepo;
    protected $masterOrganizationRepo;
    public function boot(
        DataLemburFace $dataLemburRepo,
        MasterOrganizationFace $masterOrganizationRepo,
    )
    {
        $this->dataLemburRepo = $dataLemburRepo;
        $this->masterOrganizationRepo = $masterOrganizationRepo;

    }

    public $dt = [];
    public function mount()
    {
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
    }
    public $pass;
    public function render()
    {
        return view('lembur_vendor.lembur_vendor_data');
    }
}
