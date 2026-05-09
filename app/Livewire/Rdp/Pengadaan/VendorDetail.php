<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpMasterRumahRepo;
use App\Repositories\RdpPengadaanRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class VendorDetail extends Component
{
    use WithFileUploads;

    public $data;
    public $item;
    public $fileProposal;
    public $items = [];
    public $laporan = [];
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;

    public function mount()
    {
        $this->item = RdpPengadaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_master_vendor_id !== (int) Auth::user()->rdp_master_vendors?->id, 404);

        $this->items = $this->item->rdp_pengadaan_items->map(fn ($item) => [
            'id' => $item->id,
            'nama_item' => $item->nama_item,
            'deskripsi_item' => $item->deskripsi_item,
            'jumlah' => $item->jumlah,
            'satuan' => $item->satuan,
        ])->toArray();

        foreach ($this->item->rdp_pengadaan_items as $item) {
            $this->laporan[$item->id] = [
                'narasi_hasil_pengadaan' => $item->narasi_hasil_pengadaan,
                'foto_hasil_pengadaan' => null,
            ];
        }
    }

    public function proposalRules()
    {
        return [
            'fileProposal' => 'required|file|mimes:pdf|max:10240',
            'items' => 'required|array|min:1',
            'items.*.id' => [
                'nullable',
                Rule::exists('rdp_pengadaan_items', 'id')->where('rdp_pengadaan_id', $this->data['id']),
            ],
            'items.*.nama_item' => 'required|string',
            'items.*.deskripsi_item' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => ['required', Rule::in($this->satuanList)],
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'nama_item' => '',
            'deskripsi_item' => '',
            'jumlah' => '',
            'satuan' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function wireSubmitProposal()
    {
        $form = $this->validate($this->proposalRules(), [], $this->validationAttributes);

        if (RdpPengadaanRepo::submitProposal($this->data['id'], $this->fileProposal, $form['items'], Auth::user()->rdp_master_vendors?->id)) {
            session()->flash('success', 'Proposal berhasil dikirim.');
            return redirect()->route('rdp.vendor.pengadaan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Proposal tidak bisa dikirim pada status saat ini.']);
    }

    public function wireSubmitLaporan()
    {
        $rules = [];
        $attributes = [];

        foreach ($this->item->rdp_pengadaan_items as $item) {
            $rules["laporan.{$item->id}.narasi_hasil_pengadaan"] = 'required|string';
            $rules["laporan.{$item->id}.foto_hasil_pengadaan"] = 'required|image|max:5120';
            $attributes["laporan.{$item->id}.narasi_hasil_pengadaan"] = 'Narasi hasil pengadaan';
            $attributes["laporan.{$item->id}.foto_hasil_pengadaan"] = 'Foto hasil pengadaan';
        }

        $this->validate($rules, [], $attributes);

        if (RdpPengadaanRepo::submitLaporan($this->data['id'], $this->laporan, Auth::user()->rdp_master_vendors?->id)) {
            session()->flash('success', 'Laporan hasil pengadaan berhasil dikirim.');
            return redirect()->route('rdp.vendor.pengadaan.detail', $this->data['id']);
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Laporan tidak bisa dikirim pada status saat ini.']);
    }

    public $validationAttributes = [
        'fileProposal' => 'File proposal',
        'items.*.id' => 'Item pengadaan',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_item' => 'Deskripsi item',
        'items.*.jumlah' => 'Jumlah',
        'items.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.pengadaan.vendor_detail');
    }
}
