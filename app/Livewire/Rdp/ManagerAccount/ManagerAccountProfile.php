<?php

namespace App\Livewire\Rdp\ManagerAccount;

use App\Repositories\RdpManagerAccountRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManagerAccountProfile extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [];
    public $ttd;
    public $userId;

    public function mount()
    {
        $user = Auth::user();
        abort_if(!in_array($user?->user_role_id, RdpManagerAccountRepo::ROLE_IDS, true), 403);

        $this->userId = $user->id;
        $this->form = [
            'role_name' => RdpManagerAccountRepo::getPrintRoleName($user),
            'nickname' => $user->nickname,
            'username' => $user->username,
            'password' => null,
            'ttd_old' => $user->ttd,
        ];
    }

    public function rules()
    {
        return [
            'form.role_name' => 'required|string|max:191',
            'form.nickname' => 'required',
            'form.username' => [
                'required',
                Rule::unique('user_logins', 'username')
                    ->ignore($this->userId)
                    ->whereNull('deleted_at'),
            ],
            'form.password' => 'nullable',
            'ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());
        $form['form']['ttd'] = $this->ttd;

        if (RdpManagerAccountRepo::updateSelf($this->userId, $form['form'])) {
            session()->flash('success', 'Akun manager berhasil diperbarui.');
            return redirect()->route('rdp.akun-manager.profile');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.role_name' => 'Jabatan Sebagai',
        'form.nickname' => 'Nama Manager',
        'form.username' => 'Username Login',
        'form.password' => 'Password Login',
        'ttd' => 'File Tanda Tangan',
    ];

    public function render()
    {
        return view('rdp.manager_account.manager_account_profile');
    }
}
