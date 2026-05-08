<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class KaryawanCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [];
    public $rumah;

    public function mount()
    {
        $this->form['data_employee_id'] = Auth::user()->data_employees?->id;
        $this->rumah = RdpKaryawanKeluarRepo::getCurrentRumahByEmployee($this->form['data_employee_id']);
        $this->form['rdp_master_rumah_id'] = $this->rumah?->id;
        $this->form['status'] = RdpKaryawanKeluarRepo::DEFAULT_STATUS;
    }

    public function rules()
    {
        return [
            'form.data_employee_id' => 'required|exists:data_employees,id',
            'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
            'form.nomor_sk_keluar' => 'required|string',
            'form.tanggal_sk_keluar' => 'required|date',
            'form.tanggal_keluar' => 'required|date',
            'form.file_sk_keluar' => 'required|file|max:5120',
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());
        $form['form']['status'] = RdpKaryawanKeluarRepo::DEFAULT_STATUS;

        if (RdpKaryawanKeluarRepo::create($form['form'])) {
            session()->flash('success', 'Pengajuan izin keluar berhasil ditambahkan.');
            return redirect()->route('rdp.pengajuan.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.nomor_sk_keluar' => 'Nomor SK Keluar',
        'form.tanggal_sk_keluar' => 'Tanggal SK Keluar',
        'form.tanggal_keluar' => 'Tanggal Keluar',
        'form.file_sk_keluar' => 'File SK Keluar',
        'form.rdp_master_rumah_id' => 'Rumah',
    ];

    public function render()
    {
        return view('rdp.karyawan_keluar.karyawan_create');
    }
}
