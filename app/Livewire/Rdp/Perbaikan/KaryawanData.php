<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class KaryawanData extends Component
{
    public $data;
    public $cancelId;
    public $statusList = RdpPerbaikanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setCancelId($id)
    {
        $this->cancelId = $id;
    }

    public function wireCancel()
    {
        if (RdpPerbaikanRepo::cancelByKaryawan($this->cancelId, Auth::user()->data_employees?->id)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengajuan perbaikan berhasil dibatalkan.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.perbaikan.karyawan_data');
    }
}
