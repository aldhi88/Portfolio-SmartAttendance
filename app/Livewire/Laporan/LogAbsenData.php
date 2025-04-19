<?php

namespace App\Livewire\Laporan;

use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterMinorFace;
use Livewire\Component;

class LogAbsenData extends Component
{
    public $pass = [];

    protected $masterLocationRepo;
    protected $masterMinorRepo;

    public function boot(
        MasterLocationFace $masterLocationRepo,
        MasterMinorFace $masterMinorRepo
    )
    {
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterMinorRepo = $masterMinorRepo;
    }

    public function mount()
    {
        $this->pass['master_locations'] = $this->masterLocationRepo->getAll();
        $this->pass['master_minors'] = $this->masterMinorRepo->getAll();
    }

    public function render()
    {
        return view('laporan.log_absen_data');
    }
}
