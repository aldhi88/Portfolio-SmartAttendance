<?php

namespace App\Livewire\Rdp\Permintaan;

use App\Repositories\RdpPermintaanRepo;
use Livewire\Component;

class KaryawanData extends Component
{
    public $data;
    public $statusList = RdpPermintaanRepo::STATUS_LIST;

    public function render()
    {
        return view('rdp.permintaan.karyawan_data');
    }
}
