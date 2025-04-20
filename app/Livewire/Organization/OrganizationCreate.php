<?php

namespace App\Livewire\Organization;

use Livewire\Component;

class OrganizationCreate extends Component
{
    public $pass = [];
    public function mount($data)
    {
        $this->pass = $data;
    }

    public function render()
    {
        return view('organization.organization_create');
    }
}
