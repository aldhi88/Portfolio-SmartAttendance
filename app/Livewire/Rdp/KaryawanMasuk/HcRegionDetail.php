<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Livewire\Component;

class HcRegionDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if(!in_array($this->item->status, RdpKaryawanMasukRepo::STATUS_LIST, true), 404);
    }

    public function wireApprove()
    {
        if (RdpKaryawanMasukRepo::approveHcRegion($this->data['id'])) {
            session()->flash('success', 'Izin penempatan berhasil disetujui Manager HC Region.');
            return redirect()->route('rdp.hc-region.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanMasukRepo::rejectHcRegion($this->data['id'])) {
            session()->flash('success', 'Izin penempatan berhasil ditolak Manager HC Region.');
            return redirect()->route('rdp.hc-region.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.hc_region_detail');
    }
}
