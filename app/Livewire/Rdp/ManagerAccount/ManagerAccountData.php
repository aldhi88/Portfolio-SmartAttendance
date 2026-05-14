<?php

namespace App\Livewire\Rdp\ManagerAccount;

use App\Repositories\RdpManagerAccountRepo;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManagerAccountData extends Component
{
    use WithFileUploads;

    public $data;

    public $update = [];
    public $ttd;
    public $updateId;

    #[On('setEditData')]
    public function setEditData($id)
    {
        $this->updateId = $id;
        $dtQuery = RdpManagerAccountRepo::getByKey($id);

        abort_if(!$dtQuery, 404);

        $this->update = [
            'role_name' => RdpManagerAccountRepo::getPrintRoleName($dtQuery),
            'nickname' => $dtQuery->nickname,
            'username' => $dtQuery->username,
            'password' => null,
            'ttd_old' => $dtQuery->ttd,
        ];
        $this->ttd = null;
    }

    public function editRules()
    {
        return [
            "update.role_name" => "required|string|max:191",
            "update.nickname" => "required",
            "update.username" => [
                "required",
                Rule::unique('user_logins', 'username')
                    ->ignore($this->updateId)
                    ->whereNull('deleted_at'),
            ],
            "update.password" => "",
            "ttd" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
        ];
    }

    public function wireUpdate()
    {
        $form = $this->validate($this->editRules());
        $form['update']['ttd'] = $this->ttd;

        if (RdpManagerAccountRepo::update($this->updateId, $form['update'])) {
            $this->ttd = null;
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Perubahan data berhasil disimpan.']);
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalEdit');
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        "update.role_name" => "Jabatan Sebagai",
        "update.nickname" => "Nama Manager",
        "update.username" => "Username Login",
        "update.password" => "Password Login",
        "ttd" => "File Tanda Tangan",
    ];

    public function render()
    {
        return view('rdp.manager_account.manager_account_data');
    }
}
