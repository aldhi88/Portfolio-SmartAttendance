<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class KaryawanCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [];

    public function mount()
    {
        $this->form['data_employee_id'] = Auth::user()->data_employees?->id;
        $this->form['status'] = RdpKaryawanMasukRepo::DEFAULT_STATUS;
    }

    public function rules()
    {
        return [
            'form.data_employee_id' => 'required|exists:data_employees,id',
            'form.nomor_sk_mutasi' => 'required|string',
            'form.tanggal_sk_mutasi' => 'required|date',
            'form.tanggal_mulai' => 'required|date',
            'form.file_sk_mutasi' => 'required|file|max:5120',
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());
        $form['form']['data_employee_id'] = Auth::user()->data_employees?->id;
        $form['form']['rdp_master_rumah_id'] = null;
        $form['form']['status'] = RdpKaryawanMasukRepo::DEFAULT_STATUS;

        if (RdpKaryawanMasukRepo::create($form['form'])) {
            session()->flash('success', 'Pengajuan izin penempatan berhasil ditambahkan.');
            return redirect()->route('rdp.pengajuan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.nomor_sk_mutasi' => 'Nomor SK Mutasi',
        'form.tanggal_sk_mutasi' => 'Tanggal SK Mutasi',
        'form.tanggal_mulai' => 'Tanggal Mulai',
        'form.file_sk_mutasi' => 'File SK Mutasi',
    ];

    public function render()
    {
        return view('rdp.karyawan_masuk.karyawan_create');
    }
}
