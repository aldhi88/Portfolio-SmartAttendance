<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
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
        $this->item = RdpKaryawanMasukRepo::getByKey($this->data['id']);
        $this->approveRumahId = $this->item->rdp_master_rumah_id;
        $this->dt['rumahs'] = RdpKaryawanMasukRepo::getRumahs($this->item->rdp_master_rumah_id)->toArray();
    }

    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->data['id'] = $id;
    }

    public function wireApprovePendataanAset()
    {
        if (RdpKaryawanMasukRepo::approvePendataanAset($this->data['id'])) {
            session()->flash('success', 'Pendataan aset berhasil disetujui.');
            return redirect()->route('rdp.penempatan.izin-penempatan.index');
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

        if (RdpKaryawanMasukRepo::approveBerkasAdmin($this->data['id'], $this->approveRumahId)) {
            session()->flash('success', 'Berkas pengajuan berhasil disetujui.');
            return redirect()->route('rdp.penempatan.izin-penempatan.detail', $this->data['id']);
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

        if (RdpKaryawanMasukRepo::requestRevisionBerkasAdmin($this->data['id'], $this->catatanRevisiBerkas)) {
            session()->flash('success', 'Pengajuan berhasil dikirim untuk revisi.');
            return redirect()->route('rdp.penempatan.izin-penempatan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa direvisi pada status saat ini.']);
    }

    public function wireCancelPenempatan()
    {
        if (RdpKaryawanMasukRepo::cancelByAdmin($this->data['id'])) {
            session()->flash('success', 'Pengajuan berhasil ditolak/dibatalkan.');
            return redirect()->route('rdp.penempatan.izin-penempatan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa ditolak pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.karyawan_masuk.admin_detail');
    }
}
