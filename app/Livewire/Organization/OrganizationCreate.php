<?php

namespace App\Livewire\Organization;

use App\Repositories\Interfaces\MasterOrganizationFace;
use Livewire\Component;

class OrganizationCreate extends Component
{
    public $dt = [];

    public function submit()
    {
        $form = $this->validate();
        if($this->masterOrganizationRepo->create($form['dt'])){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data Perusahaan baru berhasil ditambahkan.']);
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modal-create');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

    }

    public function rules()
    {
        return [
            "dt.name" => "required|unique:master_organizations,name,NULL,id,deleted_at,NULL",
        ];
    }

    public $validationAttributes = [
        "dt.name" => "Nama Perusahaan",
    ];

    protected $masterOrganizationRepo;
    public function boot(MasterOrganizationFace $masterOrganizationRepo)
    {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    public function render()
    {
        return view('organization.organization_create');
    }
}
