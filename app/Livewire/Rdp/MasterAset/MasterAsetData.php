<?php

namespace App\Livewire\Rdp\MasterAset;

use Livewire\Component;

class MasterAsetData extends Component
{
    public $data;

    // public function mount()
    // {
    //     dd($this->data);
    // }

    public function render()
    {
        return view('rdp.master_aset.master_aset_data');
    }
}
