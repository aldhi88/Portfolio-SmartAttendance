<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
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
    public $statusList = RdpKaryawanKeluarRepo::STATUS_LIST;
    public $item;
    public $isReviewStep = false;

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        $this->statusList = collect(RdpKaryawanKeluarRepo::STATUS_LIST)
            ->filter(fn ($status) => RdpKaryawanKeluarRepo::isBackwardOrSameStatus($this->item->status, $status))
            ->values()
            ->all();
        $this->isReviewStep = in_array($this->item->status, RdpKaryawanKeluarRepo::ADMIN_REVIEWABLE_STATUS, true);
        $this->form = $this->item->only([
            'data_employee_id',
            'rdp_master_rumah_id',
            'nomor_sk_keluar',
            'tanggal_sk_keluar',
            'tanggal_keluar',
            'catatan_revisi_berkas',
            'status',
        ]);
        $this->fileOld = $this->item->file_sk_keluar;
        $this->dt['employees'] = RdpKaryawanKeluarRepo::getEmployeesWithActiveRumah()->toArray();
        $this->dt['rumahs'] = RdpKaryawanKeluarRepo::getRumahs($this->item->rdp_master_rumah_id)->toArray();
    }

    public function rules()
    {
        if ($this->isReviewStep) {
            return [
                'form.data_employee_id' => 'required|exists:data_employees,id',
                'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
                'form.nomor_sk_keluar' => 'required|string',
                'form.tanggal_sk_keluar' => 'required|date',
                'form.tanggal_keluar' => 'required|date',
                'form.file_sk_keluar' => 'nullable|file|max:5120',
            ];
        }

        return [
            'form.data_employee_id' => 'required|exists:data_employees,id',
            'form.rdp_master_rumah_id' => 'required|exists:rdp_master_rumahs,id',
            'form.nomor_sk_keluar' => 'required|string',
            'form.tanggal_sk_keluar' => 'required|date',
            'form.tanggal_keluar' => 'required|date',
            'form.file_sk_keluar' => 'nullable|file|max:5120',
            'form.status' => ['required', Rule::in($this->statusList)],
        ];
    }

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());

        if ($this->isReviewStep) {
            $payload = $form['form'];
            $payload['status'] = RdpKaryawanKeluarRepo::SPV_APPROVED_STATUS;
            $payload['catatan_revisi_berkas'] = null;

            if (RdpKaryawanKeluarRepo::update($this->data['id'], $payload)) {
                session()->flash('success', 'Data izin keluar berhasil disimpan dan berkas pengajuan disetujui.');
                return redirect()->route('rdp.keluar-rdp.izin-keluar.index');
            }

            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
            return;
        }

        if (RdpKaryawanKeluarRepo::update($this->data['id'], $form['form'], false)) {
            session()->flash('success', 'Data izin keluar berhasil disimpan.');
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
        return collect($this->dt['rumahs'])->firstWhere('id', (int) ($this->form['rdp_master_rumah_id'] ?? 0));
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.admin_edit');
    }
}
