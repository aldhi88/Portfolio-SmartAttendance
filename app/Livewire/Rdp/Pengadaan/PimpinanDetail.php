<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Livewire\Component;

class PimpinanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpPengadaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
    }

    public function wireApprove()
    {
        if (RdpPengadaanRepo::approvePimpinan($this->data['id'])) {
            return redirect()->route('rdp.persetujuan.pengadaan.index');
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpPengadaanRepo::rejectPimpinan($this->data['id'])) {
            return redirect()->route('rdp.persetujuan.pengadaan.index');
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.pengadaan.pimpinan_detail');
    }
}
