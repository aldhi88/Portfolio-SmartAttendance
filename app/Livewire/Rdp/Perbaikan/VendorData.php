<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Component;

class VendorData extends Component
{
    public $data;
    public $statusList = RdpPerbaikanRepo::STATUS_LIST;

    public function render()
    {
        return view('rdp.perbaikan.vendor_data');
    }
}
