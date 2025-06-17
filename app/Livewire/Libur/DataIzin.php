<?php

namespace App\Livewire\Libur;

use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class DataIzin extends Component
{
    protected $dataLiburIzinRepo;
    public function boot(DataLiburIzinFace $dataLiburIzinRepo)
    {
        $this->dataLiburIzinRepo = $dataLiburIzinRepo;
    }

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function wireDelete()
    {
        if($this->dataLiburIzinRepo->delete($this->deleteId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDelete');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $deleteMultipleId;
    #[On('setDeleteMultipleId')]
    public function setDeleteMultipleId($ids)
    {
        $this->deleteMultipleId = $ids;
    }
    public function deleteMultiple()
    {
        if($this->dataLiburIzinRepo->deleteMultiple($this->deleteMultipleId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $prosesId;
    #[On('setProsesId')]
    public function setProsesId($id)
    {
        $this->prosesId = $id;
    }
    public function wireProses($proses)
    {
        $data['status'] = $proses;
        // if(!Auth::user()->data_employees){
        //     $this->dispatch('closeModal',id:'modalConfirmSetuju');
        //     $this->dispatch('alert', data:['type' => 'error',  'message' => 'User ini tidak ada akses.']);
        //     return;
        // }
        $data['approved_by'] = Auth::user()->data_employees?->id ?? null;
        if($this->dataLiburIzinRepo->process($this->prosesId, $data)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmSetuju');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil diproses.']);
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_izin');
    }
}
