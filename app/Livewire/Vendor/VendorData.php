<?php

namespace App\Livewire\Vendor;

use App\Repositories\Interfaces\DataVendorFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class VendorData extends Component
{
    protected $masterOrganizationRepo;
    protected $dataVendorRepo;
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        DataVendorFace $dataVendorRepo,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->dataVendorRepo = $dataVendorRepo;
    }

    // edit section
    public $update = [];
    public $updateId;
    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $dtQuery = $this->dataVendorRepo->getByKey($id)->toArray();
        $this->update = [
            'name' => $dtQuery['name'],
            'user_login_id' => $dtQuery['user_login_id'],
            'master_organization_id' => $dtQuery['master_organization_id'],
            'username' => $dtQuery['user_logins']['username'],
            'password' => null
        ];
    }
    public function editRules()
    {
        return [
            "update.name" => "required",
            "update.user_login_id" => "",
            "update.master_organization_id" => "",
            "update.username" => [
                "required",
                Rule::unique('user_logins', 'username')
                    ->ignore($this->update['user_login_id'])
                    ->whereNull('deleted_at')
            ],
            "update.password" => "",
        ];
    }
    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());

        if ($this->dataVendorRepo->update($this->updateId, $form['update'])) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Perubahan Data berhasil disimpan.']);
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalEdit');
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end edit section

    // create section
    public $store = [];
    public function createRules()
    {
        return [
            "store.name" => "required",
            "store.master_organization_id" => "",
            "store.username" => "required|unique:user_logins,username,NULL,id,deleted_at,NULL",
            "store.password" => "required",
        ];
    }
    public function wireStore()
    {
        $form = $this->validate($this->createRules());
        if ($this->dataVendorRepo->create($form['store'])) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalCreate');
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
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
        if ($this->dataVendorRepo->delete($this->deleteId)) {
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
        if ($this->dataVendorRepo->deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.name" => "Nama Pengguna",
        "store.username" => "Username Login",
        "store.password" => "Password Login",
        "update.name" => "Nama Pengguna",
        "update.username" => "Username Login",
        "update.password" => "Password Login",
    ];


    public $dt = [];
    public function mount()
    {
        $this->dt['org'] = ($this->masterOrganizationRepo->getAll())->toArray();
        // dd($this->all());
    }

    public $pass;
    public function render()
    {
        return view('vendor.vendor_data');
    }
}
