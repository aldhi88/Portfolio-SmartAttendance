<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpPengadaanRepo;
use Livewire\Component;

class AdminDetail extends Component
{
    public $data;
    public $item;
    public $catatanRevisi;

    public function mount()
    {
        $this->item = RdpPengadaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
    }

    public function wireRequestRevision()
    {
        $this->validate(['catatanRevisi' => 'required|string'], [], ['catatanRevisi' => 'Catatan revisi']);
        if (RdpPengadaanRepo::requestRevision($this->data['id'], $this->catatanRevisi)) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa direvisi pada status saat ini.']);
    }

    public function wireApproveProposal()
    {
        if (RdpPengadaanRepo::approveProposalAdmin($this->data['id'])) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa disetujui pada status saat ini.']);
    }

    public function wireRequestProposalRevision()
    {
        if (RdpPengadaanRepo::requestProposalRevision($this->data['id'])) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa dikembalikan pada status saat ini.']);
    }

    public function wirePublishSpk()
    {
        if (RdpPengadaanRepo::publishSpk($this->data['id'])) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'SPK tidak bisa diterbitkan pada status saat ini.']);
    }

    public function wireApproveLaporan()
    {
        if (RdpPengadaanRepo::approveLaporanAdmin($this->data['id'])) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Laporan tidak bisa disetujui pada status saat ini.']);
    }

    public function wireCancel()
    {
        if (RdpPengadaanRepo::cancelByAdmin($this->data['id'])) {
            return redirect()->route('rdp.pengadaan.detail', $this->data['id']);
        }
        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengadaan tidak bisa dibatalkan pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.pengadaan.admin_detail');
    }
}
