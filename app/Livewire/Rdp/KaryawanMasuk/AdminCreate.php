<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use App\Repositories\RdpMasterRumahRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [
        'status' => RdpKaryawanMasukRepo::ASSET_SPV_APPROVED_STATUS,
    ];
    public $aset = [];
    public $statusList = RdpKaryawanMasukRepo::ASSET_STATUS_LIST;
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;
    public $dt = [];

    public function mount()
    {
        $this->dt['employees'] = RdpKaryawanMasukRepo::getEmployees()->toArray();
        $this->dt['rumahs'] = RdpKaryawanMasukRepo::getRumahs()->toArray();
    }

    public function rules()
    {
        return [
            'form.data_employee_id' => 'required|exists:data_employees,id',
            'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
            'form.nomor_sk_mutasi' => 'required|string',
            'form.tanggal_sk_mutasi' => 'required|date',
            'form.tanggal_mulai' => 'required|date',
            'form.file_sk_mutasi' => 'required|file|max:5120',
            'aset' => 'required|array|min:1',
            'aset.*.jenis' => 'nullable|string',
            'aset.*.jumlah' => 'nullable|string',
            'aset.*.satuan' => ['nullable', Rule::in($this->satuanList)],
            'aset.*.status' => ['required', Rule::in($this->statusList)],
            'aset.*.catatan' => 'nullable|string',
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());
        $form['form']['catatan_revisi_berkas'] = null;
        $form['form']['status'] = RdpKaryawanMasukRepo::ASSET_SPV_APPROVED_STATUS;

        if (RdpKaryawanMasukRepo::createWithAsets($form['form'], $form['aset'])) {
            session()->flash('success', 'Data izin penempatan berhasil ditambahkan.');
            return redirect()->route('rdp.penempatan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function selectedEmployee()
    {
        return collect($this->dt['employees'])->firstWhere('id', (int) ($this->form['data_employee_id'] ?? 0));
    }

    public function selectedRumah()
    {
        return collect($this->dt['rumahs'])->firstWhere('id', (int) ($this->form['rdp_master_rumah_id'] ?? 0));
    }

    public function updatedFormRdpMasterRumahId()
    {
        $this->loadAsetRumah();
    }

    protected function loadAsetRumah()
    {
        $this->aset = RdpKaryawanMasukRepo::getRumahAsets($this->form['rdp_master_rumah_id'] ?? null)
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'nama' => $item->rdp_master_asets?->perlengkapan ?: '-',
                        'jenis' => $item->jenis,
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->satuan,
                        'status' => $item->status,
                        'catatan' => $item->catatan,
                    ],
                ];
            })
            ->toArray();
    }

    public $validationAttributes = [
        'form.data_employee_id' => 'Karyawan',
        'form.rdp_master_rumah_id' => 'Rumah',
        'form.nomor_sk_mutasi' => 'Nomor SK Mutasi',
        'form.tanggal_sk_mutasi' => 'Tanggal SK Mutasi',
        'form.tanggal_mulai' => 'Tanggal Mulai',
        'form.file_sk_mutasi' => 'File SK Mutasi',
        'aset' => 'Pendataan Aset',
        'aset.*.status' => 'Status Aset',
    ];

    public function render()
    {
        return view('rdp.karyawan_masuk.admin_create');
    }
}
