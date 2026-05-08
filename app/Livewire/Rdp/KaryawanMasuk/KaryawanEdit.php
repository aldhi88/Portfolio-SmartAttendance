<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
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
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        $this->isEditable = in_array($this->item->status, RdpKaryawanMasukRepo::EDITABLE_STATUS);
        $this->form = $this->item->only([
            'data_employee_id',
            'rdp_master_rumah_id',
            'nomor_sk_mutasi',
            'tanggal_sk_mutasi',
            'tanggal_mulai',
            'status',
        ]);
        $this->fileOld = $this->item->file_sk_mutasi;
    }

    public function rules()
    {
        return [
            'form.nomor_sk_mutasi' => 'required|string',
            'form.tanggal_sk_mutasi' => 'required|date',
            'form.tanggal_mulai' => 'required|date',
            'form.file_sk_mutasi' => ($this->fileOld ? 'nullable' : 'required') . '|file|max:5120',
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

        if (RdpKaryawanMasukRepo::update($this->data['id'], $form['form'])) {
            session()->flash('success', 'Pengajuan izin penempatan berhasil disimpan.');
            return redirect()->route('rdp.pengajuan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.karyawan_edit');
    }
}
