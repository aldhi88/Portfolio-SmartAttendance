<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class VendorDetail extends Component
{
    use WithFileUploads;

    public $data;
    public $item;
    public $fileProposal;
    public $laporan = [];

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_master_vendor_id !== (int) Auth::user()->rdp_master_vendors?->id, 404);

        foreach ($this->item->rdp_perbaikan_items as $item) {
            $this->laporan[$item->id] = [
                'narasi_hasil_perbaikan' => $item->narasi_hasil_perbaikan,
                'foto_hasil_perbaikan' => null,
            ];
        }
    }

    public function wireSubmitProposal()
    {
        $this->validate([
            'fileProposal' => 'required|file|mimes:pdf|max:10240',
        ], [], [
            'fileProposal' => 'File proposal',
        ]);

        if (RdpPerbaikanRepo::submitProposal($this->data['id'], $this->fileProposal, Auth::user()->rdp_master_vendors?->id)) {
            session()->flash('success', 'Proposal berhasil dikirim.');
            return redirect()->route('rdp.vendor.perbaikan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa dikirim pada status saat ini.']);
    }

    public function wireSubmitLaporan()
    {
        $rules = [];
        $attributes = [];

        foreach ($this->item->rdp_perbaikan_items as $item) {
            $rules["laporan.{$item->id}.narasi_hasil_perbaikan"] = 'required|string';
            $rules["laporan.{$item->id}.foto_hasil_perbaikan"] = 'required|image|max:5120';
            $attributes["laporan.{$item->id}.narasi_hasil_perbaikan"] = 'Narasi hasil perbaikan';
            $attributes["laporan.{$item->id}.foto_hasil_perbaikan"] = 'Foto hasil perbaikan';
        }

        $this->validate($rules, [], $attributes);

        if (RdpPerbaikanRepo::submitLaporan($this->data['id'], $this->laporan, Auth::user()->rdp_master_vendors?->id)) {
            session()->flash('success', 'Laporan hasil perbaikan berhasil dikirim.');
            return redirect()->route('rdp.vendor.perbaikan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Laporan tidak bisa dikirim pada status saat ini.']);
    }

    public function render()
    {
        return view('rdp.perbaikan.vendor_detail');
    }
}
