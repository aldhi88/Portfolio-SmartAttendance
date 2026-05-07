<?php

namespace App\Livewire\Rdp\MasterRumah;

use App\Repositories\RdpMasterRumahRepo;
use Livewire\Component;

class MasterRumahDetail extends Component
{
    public $data;
    public $rumah;

    public function mount()
    {
        $this->rumah = RdpMasterRumahRepo::getByKey($this->data['id']);
    }

    public function render()
    {
        return view('rdp.master_rumah.master_rumah_detail');
    }
}
