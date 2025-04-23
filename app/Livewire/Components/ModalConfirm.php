<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ModalConfirm extends Component
{
    public $data = [];

    public function render()
    {
        return view('components.modal.modal_confirm');
    }

    #[On('modalconfirm-prepare')]
    public function prepare($data)
    {
        $this->data = $data;
    }

    public function modalConfirmProcess()
    {
        $this->dispatch($this->data['callback'], data:$this->data);
        $this->dispatch('closeModal',id:'modalConfirm');
    }
}
