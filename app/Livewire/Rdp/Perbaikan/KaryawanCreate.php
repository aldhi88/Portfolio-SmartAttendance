<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class KaryawanCreate extends Component
{
    use WithFileUploads;

    public $data;
    public $penempatan;
    public $form = [
        'status' => RdpPerbaikanRepo::DEFAULT_STATUS,
    ];
    public $items = [];

    public function mount()
    {
        $this->penempatan = RdpPerbaikanRepo::getCurrentPenempatanByEmployee(Auth::user()->data_employees?->id);
        $this->form['rdp_karyawan_masuk_id'] = $this->penempatan?->id;
        $this->addItem();
    }

    public function rules()
    {
        return [
            'form.rdp_karyawan_masuk_id' => 'required|exists:rdp_karyawan_masuks,id',
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
        $form['form']['status'] = RdpPerbaikanRepo::DEFAULT_STATUS;

        if (RdpPerbaikanRepo::create($form['form'], $form['items'])) {
            session()->flash('success', 'Pengajuan perbaikan berhasil dikirim.');
            return redirect()->route('rdp.pengajuan.perbaikan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.rdp_karyawan_masuk_id' => 'Rumah aktif',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_kerusakan' => 'Deskripsi kerusakan',
        'items.*.foto_kerusakan' => 'Foto kerusakan',
    ];

    public function render()
    {
        return view('rdp.perbaikan.karyawan_create');
    }
}
