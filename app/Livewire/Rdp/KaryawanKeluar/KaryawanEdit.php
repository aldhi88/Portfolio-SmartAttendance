<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class KaryawanEdit extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [];
    public $item;
    public $fileOld;
    public $isEditable = false;

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);
        $this->isEditable = in_array($this->item->status, RdpKaryawanKeluarRepo::EDITABLE_STATUS);
        $this->form = $this->item->only([
            'data_employee_id',
            'rdp_master_rumah_id',
            'nomor_sk_keluar',
            'tanggal_sk_keluar',
            'tanggal_keluar',
            'status',
        ]);
        $this->fileOld = $this->item->file_sk_keluar;
    }

    public function rules()
    {
        return [
            'form.nomor_sk_keluar' => 'required|string',
            'form.tanggal_sk_keluar' => 'required|date',
            'form.tanggal_keluar' => 'required|date',
            'form.file_sk_keluar' => ($this->fileOld ? 'nullable' : 'required') . '|file|max:5120',
        ];
    }

    public function wireSubmit()
    {
        if (!$this->isEditable) {
            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa diedit pada status saat ini.']);
            return;
        }

        $form = $this->validate($this->rules());
        $form['form']['data_employee_id'] = $this->item->data_employee_id;
        $form['form']['rdp_master_rumah_id'] = $this->item->rdp_master_rumah_id;
        $form['form']['status'] = $this->item->status === 'Berkas Ditolak SPV, cek catatan'
            ? 'Pengajuan Revisi'
            : $this->item->status;

        if (RdpKaryawanKeluarRepo::update($this->data['id'], $form['form'])) {
            session()->flash('success', 'Pengajuan izin keluar berhasil disimpan.');
            return redirect()->route('rdp.pengajuan.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.karyawan_edit');
    }
}
