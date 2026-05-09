<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KaryawanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_karyawan_masuks?->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);
    }

    public function render()
    {
        return view('rdp.perbaikan.karyawan_detail');
    }
}
