<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Component;

class AdminDetail extends Component
{
    public $data;
    public $item;
    public $catatanRevisi;

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
    }

    public function wireRequestRevision()
    {
        $this->validate(['catatanRevisi' => 'required|string'], [], ['catatanRevisi' => 'Catatan revisi']);
        if (RdpPerbaikanRepo::requestRevision($this->data['id'], $this->catatanRevisi)) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa direvisi pada status saat ini.']);
    }

    public function wireApproveProposal()
    {
        if (RdpPerbaikanRepo::approveProposalAdmin($this->data['id'])) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa disetujui pada status saat ini.']);
    }

    public function wireRequestProposalRevision()
    {
        if (RdpPerbaikanRepo::requestProposalRevision($this->data['id'])) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa dikembalikan pada status saat ini.']);
    }

    public function wirePublishSpk()
    {
        if (RdpPerbaikanRepo::publishSpk($this->data['id'])) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'SPK tidak bisa diterbitkan pada status saat ini.']);
    }

    public function wireApproveLaporan()
    {
        if (RdpPerbaikanRepo::approveLaporanAdmin($this->data['id'])) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Laporan tidak bisa disetujui pada status saat ini.']);
    }

    public function wireCancel()
    {
        if (RdpPerbaikanRepo::cancelByAdmin($this->data['id'])) {
            return redirect()->route('rdp.perbaikan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Perbaikan tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.perbaikan.admin_detail');
    }
}
