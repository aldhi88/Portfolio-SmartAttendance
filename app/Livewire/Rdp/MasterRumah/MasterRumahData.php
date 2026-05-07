<?php

namespace App\Livewire\Rdp\MasterRumah;

use App\Repositories\RdpMasterRumahRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class MasterRumahData extends Component
{
    public $data;

    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }

    public function wireDelete()
    {
        if (RdpMasterRumahRepo::delete($this->deleteId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $deleteMultipleId;
    #[On('setDeleteMultipleId')]
    public function setDeleteMultipleId($ids)
    {
        $this->deleteMultipleId = $ids;
    }

    public function deleteMultiple()
    {
        if (RdpMasterRumahRepo::deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('rdp.master_rumah.master_rumah_data');
    }
}
