<?php

namespace App\Livewire\Organization;

use App\Repositories\Interfaces\MasterOrganizationFace;
use Livewire\Attributes\On;
use Livewire\Component;

class OrganizationData extends Component
{
    protected $masterOrganizationRepo;
    public function boot(MasterOrganizationFace $masterOrganizationRepo)
    {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    // edit section
    public $update = [];
    public $updateId;
    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $this->update = $this->masterOrganizationRepo->getByKey($id)
            ->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at'])
            ->toArray();
    }
    public function editRules()
    {
        return [
            "update.name" => "required|unique:master_organizations,name,{$this->updateId},id,deleted_at,NULL",
        ];
    }
    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());
        if($this->masterOrganizationRepo->update($this->updateId, $form['update'])){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Perubahan Data berhasil disimpan.']);
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalEdit');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

    }
    // end edit section

    // create section
    public $store = [];
    public function createRules()
    {
        return [
            "store.name" => "required|unique:master_organizations,name,NULL,id,deleted_at,NULL",
        ];
    }
    public function wireStore()
    {
        $form = $this->validate($this->createRules());
        if($this->masterOrganizationRepo->create($form['store'])){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalCreate');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

    }
    // end create section

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function wireDelete()
    {
        if($this->masterOrganizationRepo->delete($this->deleteId)){
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
        if($this->masterOrganizationRepo->deleteMultiple($this->deleteMultipleId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.name" => "Nama Perusahaan",
        "update.name" => "Nama Perusahaan",
    ];

    public $pass;
    public function render()
    {
        return view('organization.organization_data');
    }
}
