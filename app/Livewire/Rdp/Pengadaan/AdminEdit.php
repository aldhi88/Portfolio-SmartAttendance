<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpMasterRumahRepo;
use App\Repositories\RdpPengadaanRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminEdit extends Component
{
    public $data;
    public $item;
    public $form = [];
    public $items = [];
    public $dt = [];
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;

    public function mount()
    {
        $this->item = RdpPengadaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);

        $this->dt['vendors'] = RdpPengadaanRepo::getVendors()->toArray();
        $this->form = [
            'rdp_master_vendor_id' => $this->item->rdp_master_vendor_id,
        ];
        $this->items = $this->item->rdp_pengadaan_items->map(fn ($item) => [
            'id' => $item->id,
            'nama_item' => $item->nama_item,
            'deskripsi_item' => $item->deskripsi_item,
            'jumlah' => $item->jumlah,
            'satuan' => $item->satuan,
        ])->toArray();
    }

    public function rules()
    {
        return [
            'form.rdp_master_vendor_id' => 'required|exists:rdp_master_vendors,id',
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

    public function wireSubmit()
    {
        $form = $this->validate($this->rules());

        if (RdpPengadaanRepo::update($this->data['id'], $form['form'], $form['items'])) {
            session()->flash('success', 'Data pengadaan berhasil diperbarui.');
            return redirect()->route('rdp.pengadaan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.rdp_master_vendor_id' => 'Vendor',
        'items.*.id' => 'Item pengadaan',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_item' => 'Deskripsi item',
        'items.*.jumlah' => 'Jumlah',
        'items.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.pengadaan.admin_edit');
    }
}
