<?php

namespace App\Livewire\Lembur;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class LemburData extends Component
{
    protected $dataLemburRepo;
    protected $masterOrganizationRepo;
    public function boot(
        DataLemburFace $dataLemburRepo,
        MasterOrganizationFace $masterOrganizationRepo,
    )
    {
        $this->dataLemburRepo = $dataLemburRepo;
        $this->masterOrganizationRepo = $masterOrganizationRepo;

    }

    public $dt = [];

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function wireDelete()
    {
        if($this->dataLemburRepo->delete($this->deleteId)){
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
        if($this->dataLemburRepo->deleteMultiple($this->deleteMultipleId)){
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
        if($this->dataLemburRepo->process($this->prosesId, $data)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmSetuju');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil diproses.']);
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function mount()
    {
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
    }

    public $pass;
    public function render()
    {
        return view('lembur.lembur_data');
    }
}
