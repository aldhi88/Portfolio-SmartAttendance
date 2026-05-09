<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class KaryawanEdit extends Component
{
    use WithFileUploads;

    public $data;
    public $item;
    public $isEditable = false;
    public $form = [];
    public $items = [];

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        abort_if((int) $this->item->rdp_karyawan_masuks?->data_employee_id !== (int) Auth::user()->data_employees?->id, 404);

        $this->isEditable = in_array($this->item->status, RdpPerbaikanRepo::EDITABLE_STATUS, true);
        $this->form = [
            'rdp_karyawan_masuk_id' => $this->item->rdp_karyawan_masuk_id,
            'status' => $this->item->status,
        ];
        $this->items = $this->item->rdp_perbaikan_items->map(fn ($item) => [
            'id' => $item->id,
            'nama_item' => $item->nama_item,
            'deskripsi_kerusakan' => $item->deskripsi_kerusakan,
            'foto_kerusakan' => null,
            'foto_kerusakan_old' => $item->foto_kerusakan,
        ])->toArray();
    }

    public function rules()
    {
        $rules = [
            'form.rdp_karyawan_masuk_id' => 'required|exists:rdp_karyawan_masuks,id',
            'items' => 'required|array|min:1',
            'items.*.id' => [
                'nullable',
                Rule::exists('rdp_perbaikan_items', 'id')->where('rdp_perbaikan_id', $this->data['id']),
            ],
            'items.*.foto_kerusakan_old' => 'nullable|string',
            'items.*.nama_item' => 'required|string',
            'items.*.deskripsi_kerusakan' => 'required|string',
            'items.*.foto_kerusakan' => 'nullable|image|max:5120',
        ];

        foreach ($this->items as $index => $item) {
            if (empty($item['id']) && empty($item['foto_kerusakan'])) {
                $rules["items.{$index}.foto_kerusakan"] = 'required|image|max:5120';
            }
        }

        return $rules;
    }

    public function addItem()
    {
        $this->items[] = [
            'nama_item' => '',
            'deskripsi_kerusakan' => '',
            'foto_kerusakan' => null,
            'foto_kerusakan_old' => null,
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
        $form['form']['status'] = RdpPerbaikanRepo::REVISION_STATUS;

        if (RdpPerbaikanRepo::update($this->data['id'], $form['form'], $form['items'])) {
            session()->flash('success', 'Pengajuan perbaikan berhasil dikirim ulang.');
            return redirect()->route('rdp.pengajuan.perbaikan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'items.*.id' => 'Item perbaikan',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_kerusakan' => 'Deskripsi kerusakan',
        'items.*.foto_kerusakan' => 'Foto kerusakan',
    ];

    public function render()
    {
        return view('rdp.perbaikan.karyawan_edit');
    }
}
