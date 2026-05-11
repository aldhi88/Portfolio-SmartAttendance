<?php

namespace App\Livewire\Rdp\Pengadaan;

use App\Repositories\RdpMasterRumahRepo;
use App\Repositories\RdpPengadaanRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class KaryawanEdit extends Component
{
    public $data;
    public $item;
    public $isEditable = false;
    public $form = [];
    public $items = [];
    public $satuanList = RdpMasterRumahRepo::SATUAN_LIST;

    public function mount()
    {
        $this->item = RdpPengadaanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_karyawan_masuks?->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);

        $this->isEditable = in_array($this->item->status, RdpPengadaanRepo::EDITABLE_STATUS, true);
        $this->form = [
            'rdp_karyawan_masuk_id' => $this->item->rdp_karyawan_masuk_id,
            'status' => $this->item->status,
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
            'form.rdp_karyawan_masuk_id' => 'required|exists:rdp_karyawan_masuks,id',
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
        if (!$this->isEditable) {
            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Pengajuan tidak bisa diedit pada status saat ini.']);
            return;
        }

        $form = $this->validate($this->rules());
        $form['form']['rdp_karyawan_masuk_id'] = $this->item->rdp_karyawan_masuk_id;
        $form['form']['status'] = RdpPengadaanRepo::REVISION_STATUS;

        if (RdpPengadaanRepo::update($this->data['id'], $form['form'], $form['items'])) {
            session()->flash('success', 'Pengajuan pengadaan berhasil dikirim ulang.');
            return redirect()->route('rdp.pengajuan.pengadaan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'items.*.id' => 'Item pengadaan',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_item' => 'Deskripsi item',
        'items.*.jumlah' => 'Jumlah',
        'items.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.pengadaan.karyawan_edit');
    }
}
