<?php

namespace App\Livewire\Rdp\Permintaan;

use App\Repositories\RdpPermintaanRepo;
use Livewire\Component;

class AdminDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpPermintaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
    }

    public function wireComplete()
    {
        if (RdpPermintaanRepo::markFinished($this->data['id'])) {
            return redirect()->route('rdp.permintaan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Permintaan tidak bisa diselesaikan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.permintaan.admin_detail');
    }
}
