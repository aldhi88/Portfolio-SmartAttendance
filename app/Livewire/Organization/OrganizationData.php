<?php

namespace App\Livewire\Organization;

use Livewire\Attributes\On;
use Livewire\Component;

class OrganizationData extends Component
{
    public $pass = [];

    public function mount($data)
    {
        $this->pass = $data;
    }

    public function render()
    {
        return view('organization.organization_data');
    }
}
