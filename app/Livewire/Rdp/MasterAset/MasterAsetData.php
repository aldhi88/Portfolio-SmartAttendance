<?php

namespace App\Livewire\Rdp\MasterAset;

use App\Repositories\RdpMasterAsetRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class MasterAsetData extends Component
{
    public $data;

    // edit section
    public $update = [];
    public $updateId;
    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $this->update = RdpMasterAsetRepo::getByKey($id)
            ->makeHidden(['id', 'created_at', 'updated_at'])
            ->toArray();
    }
    public function editRules()
    {
        return [
            "update.perlengkapan" => "required|unique:rdp_master_asets,perlengkapan,{$this->updateId},id",
        ];
    }
    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());
        if (RdpMasterAsetRepo::update($this->updateId, $form['update'])) {
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
            "store.perlengkapan" => "required|unique:rdp_master_asets,perlengkapan",
        ];
    }
    public function wireStore()
    {
        $this->store['perlengkapan'] = trim($this->store['perlengkapan'] ?? '');

        $form = $this->validate($this->createRules());
        if (RdpMasterAsetRepo::create($form['store'])) {
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
        if (RdpMasterAsetRepo::delete($this->deleteId)) {
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
        if (RdpMasterAsetRepo::deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.perlengkapan" => "Nama Perlengkapan",
        "update.perlengkapan" => "Nama Perlengkapan",
    ];

    public function render()
    {
        return view('rdp.master_aset.master_aset_data');
    }
}
