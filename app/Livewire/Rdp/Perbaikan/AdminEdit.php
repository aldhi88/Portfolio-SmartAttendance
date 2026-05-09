<?php

namespace App\Livewire\Rdp\Perbaikan;

use App\Repositories\RdpPerbaikanRepo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEdit extends Component
{
    use WithFileUploads;

    public $data;
    public $item;
    public $form = [];
    public $items = [];
    public $dt = [];
    public $statusList = RdpPerbaikanRepo::STATUS_LIST;
    public $isReviewStep = false;

    public function mount()
    {
        $this->item = RdpPerbaikanRepo::getByKey($this->data['id']);
        abort_if(!$this->item, 404);
        $this->isReviewStep = in_array($this->item->status, RdpPerbaikanRepo::ADMIN_REVIEWABLE_STATUS, true);

        $this->dt['penempatans'] = RdpPerbaikanRepo::getActivePenempatans()->toArray();
        $this->dt['vendors'] = RdpPerbaikanRepo::getVendors()->toArray();
        $this->form = [
            'rdp_karyawan_masuk_id' => $this->item->rdp_karyawan_masuk_id,
            'rdp_master_vendor_id' => $this->item->rdp_master_vendor_id,
            'status' => $this->item->status,
            'catatan_revisi' => $this->item->catatan_revisi,
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
            'form.rdp_master_vendor_id' => 'nullable|exists:rdp_master_vendors,id',
            'form.status' => ['required', Rule::in(RdpPerbaikanRepo::STATUS_LIST)],
            'form.catatan_revisi' => 'nullable|string',
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

        if ($this->isReviewStep) {
            $rules['form.rdp_master_vendor_id'] = 'required|exists:rdp_master_vendors,id';
        }

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
        $form = $this->validate($this->rules());

        if ($this->isReviewStep) {
            $form['form']['status'] = RdpPerbaikanRepo::VENDOR_ASSIGNED_STATUS;
            $form['form']['catatan_revisi'] = null;
        }

        if (RdpPerbaikanRepo::update($this->data['id'], $form['form'], $form['items'])) {
            session()->flash('success', $this->isReviewStep ? 'Pengajuan perbaikan berhasil disetujui dan vendor ditugaskan.' : 'Data perbaikan berhasil diperbarui.');
            return redirect()->route('rdp.perbaikan.index');
        }

        $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function selectedPenempatan()
    {
        return collect($this->dt['penempatans'])->firstWhere('id', (int) ($this->form['rdp_karyawan_masuk_id'] ?? 0))
            ?: $this->item?->rdp_karyawan_masuks?->toArray();
    }

    public $validationAttributes = [
        'form.rdp_karyawan_masuk_id' => 'Rumah/Karyawan',
        'form.rdp_master_vendor_id' => 'Vendor',
        'form.status' => 'Status',
        'items.*.id' => 'Item perbaikan',
        'items.*.nama_item' => 'Nama item',
        'items.*.deskripsi_kerusakan' => 'Deskripsi kerusakan',
        'items.*.foto_kerusakan' => 'Foto kerusakan',
    ];

    public function render()
    {
        return view('rdp.perbaikan.admin_edit');
    }
}
