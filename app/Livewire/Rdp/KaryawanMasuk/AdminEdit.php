<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEdit extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [];
    public $dt = [];
    public $fileOld;
    public $statusList = RdpKaryawanMasukRepo::STATUS_LIST;
    public $item;
    public $isReviewStep = false;

    public function mount()
    {
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        $this->isReviewStep = in_array($this->item->status, RdpKaryawanMasukRepo::ADMIN_REVIEWABLE_STATUS, true);
        $this->form = $this->item->only([
            'data_employee_id',
            'rdp_master_rumah_id',
            'nomor_sk_mutasi',
            'tanggal_sk_mutasi',
            'tanggal_mulai',
            'catatan_revisi_berkas',
            'status',
        ]);
        $this->fileOld = $this->item->file_sk_mutasi;
        $this->dt['employees'] = RdpKaryawanMasukRepo::getEmployees()->toArray();
        $this->dt['rumahs'] = RdpKaryawanMasukRepo::getRumahs($this->item->rdp_master_rumah_id)->toArray();
    }

    public function rules()
    {
        if ($this->isReviewStep) {
            return [
                'form.data_employee_id' => 'required|exists:data_employees,id',
                'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
                'form.nomor_sk_mutasi' => 'required|string',
                'form.tanggal_sk_mutasi' => 'required|date',
                'form.tanggal_mulai' => 'required|date',
                'form.file_sk_mutasi' => 'nullable|file|max:5120',
            ];
        }

        return [
            'form.data_employee_id' => 'required|exists:data_employees,id',
            'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
            'form.nomor_sk_mutasi' => 'required|string',
            'form.tanggal_sk_mutasi' => 'required|date',
            'form.tanggal_mulai' => 'required|date',
            'form.file_sk_mutasi' => 'nullable|file|max:5120',
            'form.status' => ['required', Rule::in($this->statusList)],
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());

        if ($this->isReviewStep) {
            $payload = $form['form'];
            $payload['status'] = RdpKaryawanMasukRepo::SPV_APPROVED_STATUS;
            $payload['catatan_revisi_berkas'] = null;

            if (RdpKaryawanMasukRepo::update($this->data['id'], $payload)) {
                session()->flash('success', 'Data izin penempatan berhasil disimpan dan berkas pengajuan disetujui.');
                return redirect()->route('rdp.penempatan.izin-penempatan.index');
            }

            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
            return;
        }

        if (RdpKaryawanMasukRepo::update($this->data['id'], $form['form'])) {
            session()->flash('success', 'Data izin penempatan berhasil disimpan.');
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

    public function render()
    {
        return view('rdp.karyawan_masuk.admin_edit');
    }
}
