<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class PimpinanData extends Component
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
        if (RdpKaryawanMasukRepo::approvePimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin penempatan berhasil disetujui.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanMasukRepo::rejectPimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin penempatan berhasil ditolak.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.pimpinan_data');
    }
}
