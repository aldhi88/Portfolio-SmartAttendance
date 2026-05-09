<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class PimpinanData extends Component
{
    public $data;
    public $actionId;
    public $statusList = RdpPengadaanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireApprove()
    {
        if (RdpPengadaanRepo::approvePimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengadaan berhasil disetujui.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpPengadaanRepo::rejectPimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Proposal pengadaan berhasil ditolak.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.pengadaan.pimpinan_data');
    }
}
