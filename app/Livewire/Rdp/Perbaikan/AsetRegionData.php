<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AsetRegionData extends Component
{
    public $data;
    public $actionId;
    public $statusList = RdpPerbaikanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireApprove()
    {
        if (RdpPerbaikanRepo::approveAsetRegion($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Proposal perbaikan berhasil disetujui Manager Aset Region.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpPerbaikanRepo::rejectAsetRegion($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Proposal perbaikan berhasil ditolak Manager Aset Region.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.perbaikan.aset_region_data');
    }
}
