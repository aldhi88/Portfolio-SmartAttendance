<?php

namespace App\Livewire\Functions;

use App\Repositories\Interfaces\MasterFunctionFace;
use Livewire\Attributes\On;
use Livewire\Component;

class FunctionData extends Component
{
    protected $masterFunctionRepo;
    public function boot(MasterFunctionFace $masterFunctionRepo)
    {
        $this->masterFunctionRepo = $masterFunctionRepo;
    }

    // edit section
    public $update = [];
    public $updateId;
    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $this->update = $this->masterFunctionRepo->getByKey($id)
            ->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at'])
            ->toArray();
    }
    public function editRules()
    {
        return [
            "update.name" => "required|unique:master_functions,name,{$this->updateId},id,deleted_at,NULL",
        ];
    }
    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());
        if($this->masterFunctionRepo->update($this->updateId, $form['update'])){
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
            "store.name" => "required|unique:master_functions,name,NULL,id,deleted_at,NULL",
        ];
    }
    public function wireStore()
    {
        $form = $this->validate($this->createRules());
        if($this->masterFunctionRepo->create($form['store'])){
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
        if($this->masterFunctionRepo->delete($this->deleteId)){
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
        if($this->masterFunctionRepo->deleteMultiple($this->deleteMultipleId)){
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $validationAttributes = [
        "store.name" => "Nama Fungsi",
        "update.name" => "Nama Fungsi",
    ];

    public $pass;
    public function render()
    {
        return view('function.function_data');
    }
}
