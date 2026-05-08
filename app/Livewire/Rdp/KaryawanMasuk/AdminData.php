<?php

namespace App\Livewire\Rdp\KaryawanMasuk;

use App\Repositories\RdpKaryawanMasukRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminData extends Component
{
    public $data;

    public $deleteId;
    public $catatanRevisiBerkas;

    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }

    public function wireDelete()
    {
        if (RdpKaryawanMasukRepo::delete($this->deleteId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function wireApprovePendataanAset()
    {
        if (RdpKaryawanMasukRepo::approvePendataanAset($this->deleteId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pendataan aset berhasil disetujui.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pendataan aset tidak bisa disetujui pada status saat ini.']);
    }

    public function wireRequestRevisionBerkas()
    {
        $this->validate([
            'catatanRevisiBerkas' => 'required|string',
        ], [], [
            'catatanRevisiBerkas' => 'Catatan revisi berkas',
        ]);

        if (RdpKaryawanMasukRepo::requestRevisionBerkasAdmin($this->deleteId, $this->catatanRevisiBerkas)) {
            $this->catatanRevisiBerkas = null;
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalReviewBerkas');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengajuan berhasil dikirim untuk revisi.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa direvisi pada status saat ini.']);
    }

    public function wireCancelPenempatan()
    {
        if (RdpKaryawanMasukRepo::cancelByAdmin($this->deleteId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Pengajuan berhasil ditolak/dibatalkan.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa ditolak pada status saat ini.']);
    }

    public $statusList = RdpKaryawanMasukRepo::STATUS_LIST;

    public function render()
    {
        return view('rdp.karyawan_masuk.admin_data');
    }
}
