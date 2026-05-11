<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpMasterRumahRepo;
use App\Repositories\RdpPengadaanRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class KaryawanCreate extends Component
{
    public $data;
    public $penempatan;
    public $form = [
        'status' => RdpPengadaanRepo::DEFAULT_STATUS,
    ];
    public $items = [];
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;

    public function mount()
    {
        $this->penempatan = RdpPengadaanRepo::getCurrentPenempatanByEmployee(Auth::user()->data_employees?->id);
        $this->form['rdp_karyawan_masuk_id'] = $this->penempatan?->id;
        $this->addItem();
    }

    public function rules()
    {
        return [
            'form.rdp_karyawan_masuk_id' => 'required|exists:rdp_karyawan_masuks,id',
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
        $form['form']['rdp_karyawan_masuk_id'] = RdpPengadaanRepo::getCurrentPenempatanByEmployee(Auth::user()->data_employees?->id)?->id;
        $form['form']['status'] = RdpPengadaanRepo::DEFAULT_STATUS;

        if (RdpPengadaanRepo::create($form['form'], $form['items'])) {
            session()->flash('success', 'Pengajuan pengadaan berhasil dikirim.');
            return redirect()->route('rdp.pengajuan.pengadaan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.rdp_karyawan_masuk_id' => 'Rumah aktif',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_item' => 'Deskripsi item',
        'items.*.jumlah' => 'Jumlah',
        'items.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.pengadaan.karyawan_create');
    }
}
