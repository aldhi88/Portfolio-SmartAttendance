<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use App\Repositories\RdpMasterRumahRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [
        'status' => RdpKaryawanKeluarRepo::ASSET_SPV_APPROVED_STATUS,
    ];
    public $aset = [];
    public $statusList = RdpKaryawanKeluarRepo::ASSET_STATUS_LIST;
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;
    public $dt = [];

    public function mount()
    {
        $this->dt['employees'] = RdpKaryawanKeluarRepo::getEmployeesWithActiveRumah()->toArray();
        $this->dt['rumahs'] = [];
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
        $form['form']['status'] = RdpKaryawanKeluarRepo::ASSET_SPV_APPROVED_STATUS;

        if (RdpKaryawanKeluarRepo::createWithAsets($form['form'], $form['aset'])) {
            session()->flash('success', 'Data izin keluar berhasil ditambahkan.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function selectedEmployee()
    {
        return collect($this->dt['employees'])->firstWhere('id', (int) ($this->form['data_employee_id'] ?? 0));
    }

    public function selectedRumah()
    {
        if (empty($this->form['rdp_master_rumah_id'])) {
            return null;
        }

        return RdpKaryawanKeluarRepo::getCurrentRumahByEmployee($this->form['data_employee_id'] ?? null);
    }

    public function updatedFormDataEmployeeId()
    {
        $rumah = RdpKaryawanKeluarRepo::getCurrentRumahByEmployee($this->form['data_employee_id'] ?? null);
        $this->form['rdp_master_rumah_id'] = $rumah?->id;
        $this->loadAsetRumah();
    }

    protected function loadAsetRumah()
    {
        $this->aset = RdpKaryawanKeluarRepo::getRumahAsets($this->form['rdp_master_rumah_id'] ?? null)
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
        'form.nomor_sk_keluar' => 'Nomor SK Keluar',
        'form.tanggal_sk_keluar' => 'Tanggal SK Keluar',
        'form.tanggal_keluar' => 'Tanggal Keluar',
        'form.file_sk_keluar' => 'File SK Keluar',
        'aset' => 'Pendataan Aset',
        'aset.*.status' => 'Status Aset',
    ];

    public function render()
    {
        return view('rdp.karyawan_keluar.admin_create');
    }
}
