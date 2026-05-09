<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $form = [
        'status' => RdpPerbaikanRepo::VENDOR_ASSIGNED_STATUS,
    ];
    public $items = [];
    public $dt = [];

    public function mount()
    {
        $this->dt['penempatans'] = RdpPerbaikanRepo::getActivePenempatans()->toArray();
        $this->dt['vendors'] = RdpPerbaikanRepo::getVendors()->toArray();
        $this->addItem();
    }

    public function rules()
    {
        return [
            'form.rdp_karyawan_masuk_id' => 'required|exists:rdp_karyawan_masuks,id',
            'form.rdp_master_vendor_id' => 'required|exists:rdp_master_vendors,id',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string',
            'items.*.deskripsi_kerusakan' => 'required|string',
            'items.*.foto_kerusakan' => 'required|image|max:5120',
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'nama_item' => '',
            'deskripsi_kerusakan' => '',
            'foto_kerusakan' => null,
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
        $form['form']['status'] = RdpPerbaikanRepo::VENDOR_ASSIGNED_STATUS;

        if (RdpPerbaikanRepo::create($form['form'], $form['items'])) {
            session()->flash('success', 'Data perbaikan berhasil ditambahkan.');
            return redirect()->route('rdp.perbaikan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function selectedPenempatan()
    {
        return collect($this->dt['penempatans'])->firstWhere('id', (int) ($this->form['rdp_karyawan_masuk_id'] ?? 0));
    }

    public $validationAttributes = [
        'form.rdp_karyawan_masuk_id' => 'Rumah/Karyawan',
        'form.rdp_master_vendor_id' => 'Vendor',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_kerusakan' => 'Deskripsi kerusakan',
        'items.*.foto_kerusakan' => 'Foto kerusakan',
    ];

    public function render()
    {
        return view('rdp.perbaikan.admin_create');
    }
}
