<?php

namespace App\Livewire\Rdp\KaryawanKeluar;

use App\Repositories\RdpKaryawanKeluarRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminDetail extends Component
{
    public $data;
    public $item;
    public $catatanRevisiBerkas;
    public $approveRumahId;
    public $dt = [];

    public function mount()
    {
        $this->item = RdpKaryawanKeluarRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        $this->approveRumahId = $this->item->rdp_master_rumah_id;
        $this->dt['rumahs'] = RdpKaryawanKeluarRepo::getRumahs($this->item->rdp_master_rumah_id)->toArray();
    }

    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->data['id'] = $id;
    }

    public function wireApprovePendataanAset()
    {
        if (RdpKaryawanKeluarRepo::approvePendataanAset($this->data['id'])) {
            session()->flash('success', 'Pendataan aset berhasil disetujui.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pendataan aset tidak bisa disetujui pada status saat ini.']);
    }

    public function wireApproveBerkas()
    {
        $this->validate([
            'approveRumahId' => 'required|exists:rdp_master_rumahs,id',
        ], [], [
            'approveRumahId' => 'Unit Rumah',
        ]);

        if (RdpKaryawanKeluarRepo::approveBerkasAdmin($this->data['id'], $this->approveRumahId)) {
            session()->flash('success', 'Berkas pengajuan berhasil disetujui.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Berkas tidak bisa disetujui pada status saat ini.']);
    }

    public function wireRequestRevisionBerkas()
    {
        $this->validate([
            'catatanRevisiBerkas' => 'required|string',
        ], [], [
            'catatanRevisiBerkas' => 'Catatan revisi berkas',
        ]);

        if (RdpKaryawanKeluarRepo::requestRevisionBerkasAdmin($this->data['id'], $this->catatanRevisiBerkas)) {
            session()->flash('success', 'Pengajuan berhasil dikirim untuk revisi.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa direvisi pada status saat ini.']);
    }

    public function wireCancelKeluarRdp()
    {
        if (RdpKaryawanKeluarRepo::cancelByAdmin($this->data['id'])) {
            session()->flash('success', 'Pengajuan berhasil ditolak/dibatalkan.');
            return redirect()->route('rdp.keluar-rdp.izin-keluar.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_keluar.admin_detail');
    }
}
