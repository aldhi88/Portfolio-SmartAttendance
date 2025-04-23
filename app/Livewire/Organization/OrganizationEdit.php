<?php

namespace App\Livewire\Organization;

use App\Repositories\Interfaces\MasterOrganizationFace;
use Livewire\Attributes\On;
use Livewire\Component;

class OrganizationEdit extends Component
{
    public $dt = [];
    public $editId;
    protected $masterOrganizationRepo;
    public function boot(MasterOrganizationFace $masterOrganizationRepo)
    {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    #[On('organizationEdit-editPrepare')]
    public function editPrepare($id)
    {
        $this->editId = $id;
        $this->dt = $this->masterOrganizationRepo->getByKey($id)->toArray();
    }

    public function submit()
    {
        $form = $this->validate();
        if($this->masterOrganizationRepo->update($this->editId, $form['dt'])){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Perubahan data berhasil ditambahkan.']);
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modal-edit');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

    }

    public function rules()
    {
        return [
            "dt.name" => "required|unique:master_organizations,name,{$this->editId},id,deleted_at,NULL",
        ];
    }

    public $validationAttributes = [
        "dt.name" => "Nama Perusahaan",
    ];

    public function render()
    {
        return view('organization.organization_edit');
    }
}
