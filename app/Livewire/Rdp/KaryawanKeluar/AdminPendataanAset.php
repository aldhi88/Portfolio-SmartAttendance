<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use App\Repositories\RdpMasterRumahRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminPendataanAset extends Component
{
    public $data;
    public $item;
    public $aset = [];
    public $statusList = RdpKaryawanKeluarRepo::ASSET_STATUS_LIST;
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;
    public $isEditable = false;
    public $canViewAset = false;

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        $this->isEditable = $this->item->status === RdpKaryawanKeluarRepo::PIMPINAN_APPROVED_STATUS;
        $this->canViewAset = in_array($this->item->status, [
            RdpKaryawanKeluarRepo::PIMPINAN_APPROVED_STATUS,
            RdpKaryawanKeluarRepo::ASSET_SUBMITTED_STATUS,
            RdpKaryawanKeluarRepo::ASSET_SPV_APPROVED_STATUS,
            RdpKaryawanKeluarRepo::FINISHED_STATUS,
        ]);

        if ($this->canViewAset) {
            $this->aset = RdpKaryawanKeluarRepo::getPendataanAsets($this->data['id'])
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
    }

    public function rules()
    {
        return [
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
        if (!$this->isEditable) {
            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pendataan aset tidak bisa dilakukan pada status saat ini.']);
            return;
        }

        $form = $this->validate($this->rules());

        if (RdpKaryawanKeluarRepo::submitPendataanAsets(
            $this->data['id'],
            $form['aset'],
            RdpKaryawanKeluarRepo::ASSET_SPV_APPROVED_STATUS
        )) {
            session()->flash('success', 'Pendataan aset berhasil dikirim.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.admin_pendataan_aset');
    }
}
