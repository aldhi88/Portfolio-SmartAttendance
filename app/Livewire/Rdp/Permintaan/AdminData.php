<?php

namespace App\Livewire\Rdp\Permintaan;

use App\Repositories\RdpPermintaanRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminData extends Component
{
    public $data;
    public $actionId;
    public $statusList = RdpPermintaanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireComplete()
    {
        if (RdpPermintaanRepo::markFinished($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Permintaan berhasil diselesaikan.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Permintaan tidak bisa diselesaikan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.permintaan.admin_data');
    }
}
