<?php

namespace App\Livewire\Setting;

use App\Repositories\Interfaces\UserRoleFace;
use Livewire\Component;

class AuthorizeData extends Component
{
    protected $userRoleRepo;
    public function boot(UserRoleFace $userRoleRepo)
    {
        $this->userRoleRepo = $userRoleRepo;
    }

    // // delete section
    // public $deleteId;
    // #[On('setDeleteId')]
    // public function setDeleteId($id)
    // {
    //     $this->deleteId = $id;
    // }
    // public function wireDelete()
    // {
    //     if($this->masterOrganizationRepo->delete($this->deleteId)){
    //         $this->dispatch('reloadDT',data:'dtTable');
    //         $this->dispatch('closeModal',id:'modalConfirmDelete');
    //         $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil dihapus.']);
    //         return;
    //     }

    //     $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    // }

    // public $deleteMultipleId;
    // #[On('setDeleteMultipleId')]
    // public function setDeleteMultipleId($ids)
    // {
    //     $this->deleteMultipleId = $ids;
    // }
    // public function deleteMultiple()
    // {
    //     if($this->masterOrganizationRepo->deleteMultiple($this->deleteMultipleId)){
    //         $this->dispatch('reloadDT',data:'dtTable');
    //         $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
    //         $this->dispatch('alert', data:['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
    //         return;
    //     }
    //     $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    // }
    // // end delete section

    // public $validationAttributes = [
    //     "store.name" => "Nama Perusahaan",
    //     "update.name" => "Nama Perusahaan",
    // ];

    public $pass;
    public function render()
    {
        return view('setting.authorize_data');
    }
}
