<?php

namespace App\Livewire\Organization;

use App\Repositories\Interfaces\MasterOrganizationFace;
use Livewire\Component;

class OrganizationDelete extends Component
{
    public $msg;

    protected $masterOrganizationRepo;
    public function boot(MasterOrganizationFace $masterOrganizationRepo)
    {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    public function delete($id)
    {
        if($this->masterOrganizationRepo->delete($id)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modal-delete');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data Perusahaan berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('organization.organization_delete');
    }
}
