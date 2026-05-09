<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminData extends Component
{
    public $data;
    public $actionId;
    public $statusList = RdpPengadaanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireDelete()
    {
        if (RdpPengadaanRepo::delete($this->actionId)) {
            $this->success('Data pengadaan berhasil dihapus.', 'modalConfirmDelete');
            return;
        }

        $this->error();
    }

    public function wireRequestProposalRevision()
    {
        if (RdpPengadaanRepo::requestProposalRevision($this->actionId)) {
            $this->success('Proposal dikembalikan ke vendor.', 'modalConfirmDelete');
            return;
        }

        $this->error('Proposal tidak bisa dikembalikan pada status saat ini.');
    }

    public function wireApproveProposal()
    {
        if (RdpPengadaanRepo::approveProposalAdmin($this->actionId)) {
            $this->success('Proposal berhasil disetujui dan diteruskan ke pimpinan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Proposal tidak bisa disetujui pada status saat ini.');
    }

    public function wirePublishSpk()
    {
        if (RdpPengadaanRepo::publishSpk($this->actionId)) {
            $this->success('SPK berhasil diterbitkan. Pekerjaan pengadaan berjalan.', 'modalConfirmDelete');
            return;
        }

        $this->error('SPK tidak bisa diterbitkan pada status saat ini.');
    }

    public function wireApproveLaporan()
    {
        if (RdpPengadaanRepo::approveLaporanAdmin($this->actionId)) {
            $this->success('Laporan vendor berhasil disetujui dan diteruskan ke pimpinan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Laporan tidak bisa disetujui pada status saat ini.');
    }

    public function wireCancel()
    {
        if (RdpPengadaanRepo::cancelByAdmin($this->actionId)) {
            $this->success('Pengadaan berhasil dibatalkan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Pengadaan tidak bisa dibatalkan pada status saat ini.');
    }

    protected function success($message, $modalId)
    {
        $this->dispatch('reloadDT', data: 'dtTable');
        $this->dispatch('closeModal', id: $modalId);
        $this->dispatch('alert', data: ['type' => 'success', 'message' => $message]);
    }

    protected function error($message = 'Terjadi masalah, hubungi administrator.')
    {
        $this->dispatch('alert', data: ['type' => 'error', 'message' => $message]);
    }

    public function render()
    {
        return view('rdp.pengadaan.admin_data');
    }
}
