<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KaryawanData extends Component
{
    public $data;
    public $cancelId;
    public $statusList = RdpPengadaanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setCancelId($id)
    {
        $this->cancelId = $id;
    }

    public function wireCancel()
    {
        if (RdpPengadaanRepo::cancelByKaryawan($this->cancelId, Auth::user()->data_employees?->id)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengajuan pengadaan berhasil dibatalkan.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.pengadaan.karyawan_data');
    }
}
