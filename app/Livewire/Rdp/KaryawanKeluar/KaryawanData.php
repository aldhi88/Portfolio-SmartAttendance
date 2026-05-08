<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class KaryawanData extends Component
{
    public $data;

    public $cancelId;

    #[On('setDeleteId')]
    public function setCancelId($id)
    {
        $this->cancelId = $id;
    }

    public function wireCancel()
    {
        if (RdpKaryawanKeluarRepo::cancel($this->cancelId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengajuan berhasil dibatalkan.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa dibatalkan pada status saat ini.']);
    }

    public $statusList = RdpKaryawanKeluarRepo::STATUS_LIST;

    public function render()
    {
        return view('rdp.karyawan_keluar.karyawan_data');
    }
}
