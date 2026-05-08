<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Livewire\Component;

class PimpinanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
    }

    public function wireApprove()
    {
        if (RdpKaryawanMasukRepo::approvePimpinan($this->data['id'])) {
            session()->flash('success', 'Izin penempatan berhasil disetujui.');
            return redirect()->route('rdp.persetujuan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanMasukRepo::rejectPimpinan($this->data['id'])) {
            session()->flash('success', 'Izin penempatan berhasil ditolak.');
            return redirect()->route('rdp.persetujuan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.pimpinan_detail');
    }
}
