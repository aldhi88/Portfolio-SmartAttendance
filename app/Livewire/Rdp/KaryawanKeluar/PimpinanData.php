<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class PimpinanData extends Component
{
    public $data;

    public $actionId;

    public $statusList = RdpKaryawanKeluarRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireApprove()
    {
        if (RdpKaryawanKeluarRepo::approvePimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin keluar RDP berhasil disetujui.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanKeluarRepo::rejectPimpinan($this->actionId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Izin keluar RDP berhasil ditolak.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.pimpinan_data');
    }
}
