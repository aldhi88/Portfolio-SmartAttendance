<?php

namespace App\Livewire;

use App\Repositories\RdpManagerAccountRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserSignatureModal extends Component
{
    use WithFileUploads;

    public $ttd;
    public $currentTtd;
    public $currentTtdUrl;
    public $storageDir;
    public $targetType;

    public function mount()
    {
        $this->setCurrentSignature();
    }

    public function rules()
    {
        return [
            'ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }

    public function wireSubmit()
    {
        $this->validate();

        try {
            $user = Auth::user()->loadMissing('data_employees');
            $fileName = uniqid('ttd_', true) . '.' . $this->ttd->extension();
            $this->ttd->storeAs($this->storageDir, $fileName, 'public');

            if ($this->currentTtd) {
                Storage::disk('public')->delete($this->storageDir . '/' . $this->currentTtd);
            }

            if ($this->targetType === 'employee') {
                $user->data_employees->update(['ttd' => $fileName]);
            } else {
                $user->update(['ttd' => $fileName]);
            }

            $this->ttd = null;
            $this->setCurrentSignature();

            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Tanda tangan berhasil diperbarui.']);
            $this->dispatch('closeModal', id: 'modalEditSignature');
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
        }
    }

    protected function setCurrentSignature()
    {
        $user = Auth::user()->loadMissing('data_employees');

        if ($user->data_employees) {
            $this->targetType = 'employee';
            $this->storageDir = 'employees/ttd';
            $this->currentTtd = $user->data_employees->ttd;
        } else {
            $this->targetType = 'user_login';
            $this->storageDir = in_array($user->user_role_id, RdpManagerAccountRepo::ROLE_IDS, true)
                ? RdpManagerAccountRepo::FILE_DIR_TTD
                : 'user-logins/ttd';
            $this->currentTtd = $user->ttd;
        }

        $this->currentTtdUrl = $this->currentTtd
            ? asset('storage/' . $this->storageDir . '/' . $this->currentTtd)
            : null;
    }

    public $validationAttributes = [
        'ttd' => 'File Tanda Tangan',
    ];

    public function render()
    {
        return view('user.user_signature_modal');
    }
}
