<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Livewire\Component;

class PimpinanDetail extends Component
{
    public $data;
    public $item;

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if(!in_array($this->item->status, RdpKaryawanKeluarRepo::PIMPINAN_VISIBLE_STATUS, true), 404);
    }

    public function wireApprove()
    {
        if (RdpKaryawanKeluarRepo::approvePimpinan($this->data['id'])) {
            session()->flash('success', 'Izin keluar RDP berhasil disetujui.');
            return redirect()->route('rdp.persetujuan.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa disetujui pada status saat ini.']);
    }

    public function wireReject()
    {
        if (RdpKaryawanKeluarRepo::rejectPimpinan($this->data['id'])) {
            session()->flash('success', 'Izin keluar RDP berhasil ditolak.');
            return redirect()->route('rdp.persetujuan.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Data tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.pimpinan_detail');
    }
}
