<?php

namespace App\Livewire\Rdp\MasterVendor;

use App\Repositories\RdpMasterVendorRepo;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class MasterVendorData extends Component
{
    public $data;

    public $statusOptions = ['Aktif', 'Tidak Aktif'];

    // edit section
    public $update = [];
    public $updateId;

    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $dtQuery = RdpMasterVendorRepo::getByKey($id);

        $this->update = [
            'nama' => $dtQuery->nama,
            'telp' => $dtQuery->telp,
            'alamat' => $dtQuery->alamat,
            'status' => $dtQuery->status,
            'user_login_id' => $dtQuery->user_login_id,
            'username' => $dtQuery->user_logins->username,
            'password' => null,
        ];
    }

    public function editRules()
    {
        return [
            "update.nama" => "required",
            "update.telp" => "required",
            "update.alamat" => "required",
            "update.status" => ["required", Rule::in($this->statusOptions)],
            "update.user_login_id" => "",
            "update.username" => [
                "required",
                Rule::unique('user_logins', 'username')
                    ->ignore($this->update['user_login_id'])
                    ->whereNull('deleted_at'),
            ],
            "update.password" => "",
        ];
    }

    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());

        if (RdpMasterVendorRepo::update($this->updateId, $form['update'])) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Perubahan Data berhasil disimpan.']);
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalEdit');
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end edit section

    // create section
    public $store = [
        'status' => RdpMasterVendorRepo::DEFAULT_STATUS,
    ];

    public function createRules()
    {
        return [
            "store.nama" => "required",
            "store.telp" => "required",
            "store.alamat" => "required",
            "store.status" => ["required", Rule::in($this->statusOptions)],
            "store.username" => "required|unique:user_logins,username,NULL,id,deleted_at,NULL",
            "store.password" => "required",
        ];
    }

    public function wireStore()
    {
        $form = $this->validate($this->createRules());

        if (RdpMasterVendorRepo::create($form['store'])) {
            $this->reset('store');
            $this->store['status'] = RdpMasterVendorRepo::DEFAULT_STATUS;
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
        if (RdpMasterVendorRepo::delete($this->deleteId)) {
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
        if (RdpMasterVendorRepo::deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.nama" => "Nama Vendor",
        "store.telp" => "No. Telepon",
        "store.alamat" => "Alamat",
        "store.status" => "Status",
        "store.username" => "Username Login",
        "store.password" => "Password Login",
        "update.nama" => "Nama Vendor",
        "update.telp" => "No. Telepon",
        "update.alamat" => "Alamat",
        "update.status" => "Status",
        "update.username" => "Username Login",
        "update.password" => "Password Login",
    ];

    public function render()
    {
        return view('rdp.master_vendor.master_vendor_data');
    }
}
