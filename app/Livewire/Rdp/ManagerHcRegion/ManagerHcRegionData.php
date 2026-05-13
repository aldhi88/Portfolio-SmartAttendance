<?php

namespace App\Livewire\Rdp\ManagerHcRegion;

use App\Repositories\RdpManagerHcRegionRepo;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ManagerHcRegionData extends Component
{
    public $data;

    // edit section
    public $update = [];
    public $updateId;

    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $dtQuery = RdpManagerHcRegionRepo::getByKey($id);

        $this->update = [
            'nickname' => $dtQuery->nickname,
            'username' => $dtQuery->username,
            'password' => null,
        ];
    }

    public function editRules()
    {
        return [
            "update.nickname" => "required",
            "update.username" => [
                "required",
                Rule::unique('user_logins', 'username')
                    ->ignore($this->updateId)
                    ->whereNull('deleted_at'),
            ],
            "update.password" => "",
        ];
    }

    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());

        if (RdpManagerHcRegionRepo::update($this->updateId, $form['update'])) {
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
            "store.nickname" => "required",
            "store.username" => "required|unique:user_logins,username,NULL,id,deleted_at,NULL",
            "store.password" => "required",
        ];
    }

    public function wireStore()
    {
        $form = $this->validate($this->createRules());

        if (RdpManagerHcRegionRepo::create($form['store'])) {
            $this->reset('store');
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
        if (RdpManagerHcRegionRepo::delete($this->deleteId)) {
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
        if (RdpManagerHcRegionRepo::deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.nickname" => "Nama Manager",
        "store.username" => "Username Login",
        "store.password" => "Password Login",
        "update.nickname" => "Nama Manager",
        "update.username" => "Username Login",
        "update.password" => "Password Login",
    ];

    public function render()
    {
        return view('rdp.manager_hc_region.manager_hc_region_data');
    }
}
