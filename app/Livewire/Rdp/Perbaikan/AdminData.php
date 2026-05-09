<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminData extends Component
{
    public $data;
    public $actionId;
    public $catatanRevisi;
    public $statusList = RdpPerbaikanRepo::STATUS_LIST;

    #[On('setDeleteId')]
    public function setActionId($id)
    {
        $this->actionId = $id;
    }

    public function wireDelete()
    {
        if (RdpPerbaikanRepo::delete($this->actionId)) {
            $this->success('Data perbaikan berhasil dihapus.', 'modalConfirmDelete');
            return;
        }

        $this->error();
    }

    public function wireRequestRevision()
    {
        $this->validate(['catatanRevisi' => 'required|string'], [], ['catatanRevisi' => 'Catatan revisi']);

        if (RdpPerbaikanRepo::requestRevision($this->actionId, $this->catatanRevisi)) {
            $this->catatanRevisi = null;
            $this->success('Pengajuan berhasil dikirim untuk revisi.', 'modalReviewPerbaikan');
            return;
        }

        $this->error('Pengajuan tidak bisa direvisi pada status saat ini.');
    }

    public function wireRequestProposalRevision()
    {
        if (RdpPerbaikanRepo::requestProposalRevision($this->actionId)) {
            $this->success('Proposal dikembalikan ke vendor.', 'modalConfirmDelete');
            return;
        }

        $this->error('Proposal tidak bisa dikembalikan pada status saat ini.');
    }

    public function wireApproveProposal()
    {
        if (RdpPerbaikanRepo::approveProposalAdmin($this->actionId)) {
            $this->success('Proposal berhasil disetujui dan diteruskan ke pimpinan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Proposal tidak bisa disetujui pada status saat ini.');
    }

    public function wirePublishSpk()
    {
        if (RdpPerbaikanRepo::publishSpk($this->actionId)) {
            $this->success('SPK berhasil diterbitkan. Pekerjaan perbaikan berjalan.', 'modalConfirmDelete');
            return;
        }

        $this->error('SPK tidak bisa diterbitkan pada status saat ini.');
    }

    public function wireApproveLaporan()
    {
        if (RdpPerbaikanRepo::approveLaporanAdmin($this->actionId)) {
            $this->success('Laporan vendor berhasil disetujui dan diteruskan ke pimpinan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Laporan tidak bisa disetujui pada status saat ini.');
    }

    public function wireCancel()
    {
        if (RdpPerbaikanRepo::cancelByAdmin($this->actionId)) {
            $this->success('Perbaikan berhasil dibatalkan.', 'modalConfirmDelete');
            return;
        }

        $this->error('Perbaikan tidak bisa dibatalkan pada status saat ini.');
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
        return view('rdp.perbaikan.admin_data');
    }
}
