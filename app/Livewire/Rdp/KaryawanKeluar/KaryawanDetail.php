<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Livewire\Component;

class KaryawanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.karyawan_detail');
    }
}
