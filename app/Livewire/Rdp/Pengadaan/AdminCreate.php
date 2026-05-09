<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpMasterRumahRepo;
use App\Repositories\RdpPengadaanRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminCreate extends Component
{
    public $data;
    public $form = [
        'status' => RdpPengadaanRepo::VENDOR_ASSIGNED_STATUS,
    ];
    public $items = [];
    public $dt = [];
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;

    public function mount()
    {
        $this->dt['vendors'] = RdpPengadaanRepo::getVendors()->toArray();
        $this->addItem();
    }

    public function rules()
    {
        return [
            'form.rdp_master_vendor_id' => 'required|exists:rdp_master_vendors,id',
            'items' => 'required|array|min:1',
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
        $form['form']['status'] = RdpPengadaanRepo::VENDOR_ASSIGNED_STATUS;

        if (RdpPengadaanRepo::create($form['form'], $form['items'])) {
            session()->flash('success', 'Data pengadaan berhasil ditambahkan.');
            return redirect()->route('rdp.pengadaan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.rdp_master_vendor_id' => 'Vendor',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_item' => 'Deskripsi item',
        'items.*.jumlah' => 'Jumlah',
        'items.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.pengadaan.admin_create');
    }
}
