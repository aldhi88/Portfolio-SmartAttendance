<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Component;

class PimpinanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
    }

    public function wireApprove()
    {
        if (RdpPerbaikanRepo::approvePimpinan($this->data['id'])) {
            return redirect()->route('rdp.persetujuan.perbaikan.index');
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpPerbaikanRepo::rejectPimpinan($this->data['id'])) {
            return redirect()->route('rdp.persetujuan.perbaikan.index');
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.perbaikan.pimpinan_detail');
    }
}
