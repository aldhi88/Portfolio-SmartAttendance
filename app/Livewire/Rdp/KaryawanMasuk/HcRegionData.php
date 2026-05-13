<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class HcRegionData extends Component
{
    public $data;

    public $actionId;

    public $statusList = RdpKaryawanMasukRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireApprove()
    {
        if (RdpKaryawanMasukRepo::approveHcRegion($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin penempatan berhasil disetujui Manager HC Region.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanMasukRepo::rejectHcRegion($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin penempatan berhasil ditolak Manager HC Region.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.hc_region_data');
    }
}
