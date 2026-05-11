<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KaryawanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.karyawan_detail');
    }
}
