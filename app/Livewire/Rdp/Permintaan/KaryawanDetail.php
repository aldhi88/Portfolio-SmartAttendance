<?php

namespace App\Livewire\Rdp\Permintaan;

use App\Repositories\RdpPermintaanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KaryawanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpPermintaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_karyawan_masuks?->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);
    }

    public function render()
    {
        return view('rdp.permintaan.karyawan_detail');
    }
}
