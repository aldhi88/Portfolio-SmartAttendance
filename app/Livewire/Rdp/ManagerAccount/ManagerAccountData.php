<?php

namespace App\Livewire\Rdp\ManagerAccount;

use App\Repositories\RdpManagerAccountRepo;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ManagerAccountData extends Component
{
    public $data;

    public $update = [];
    public $updateId;

    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $dtQuery = RdpManagerAccountRepo::getByKey($id);

        abort_if(!$dtQuery, 404);

        $this->update = [
            'role_name' => $dtQuery->user_roles?->name,
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

        if (RdpManagerAccountRepo::update($this->updateId, $form['update'])) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Perubahan data berhasil disimpan.']);
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalEdit');
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        "update.nickname" => "Nama Manager",
        "update.username" => "Username Login",
        "update.password" => "Password Login",
    ];

    public function render()
    {
        return view('rdp.manager_account.manager_account_data');
    }
}
