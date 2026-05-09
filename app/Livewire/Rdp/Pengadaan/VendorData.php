<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Livewire\Component;

class VendorData extends Component
{
    public $data;
    public $statusList = RdpPengadaanRepo::STATUS_LIST;

    public function render()
    {
        return view('rdp.pengadaan.vendor_data');
    }
}
