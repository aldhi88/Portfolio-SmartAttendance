<?php

namespace App\Livewire\Organization;

use Livewire\Component;

class OrgEmail extends Component
{
    public $form = [null];

    public function removeEmail()
    {

    }

    public function simpan()
    {
        $this->validate();
    }

    public function addEmail()
    {
        $this->form[] = null;
    }

    public function rules()
    {
        return [
            "form.*" => "required"
        ];
    }

    public function mount()
    {
        // dd(count($this->form));
    }

    public $pass;
    public function render()
    {
        return view('organization.org_email');
    }
}
