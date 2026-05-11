<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use App\Repositories\RdpMasterRumahRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class KaryawanPendataanAset extends Component
{
    public $data;
    public $item;
    public $aset = [];
    public $statusList = RdpKaryawanMasukRepo::ASSET_STATUS_LIST;
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;
    public $isEditable = false;

    public function mount()
    {
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);
        $this->isEditable = $this->item->status === RdpKaryawanMasukRepo::PIMPINAN_APPROVED_STATUS;

        if ($this->isEditable) {
            $this->aset = RdpKaryawanMasukRepo::getPendataanAsets($this->data['id'])
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

        if (RdpKaryawanMasukRepo::submitPendataanAsets($this->data['id'], $form['aset'])) {
            session()->flash('success', 'Pendataan aset berhasil dikirim.');
            return redirect()->route('rdp.pengajuan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.karyawan_pendataan_aset');
    }
}
