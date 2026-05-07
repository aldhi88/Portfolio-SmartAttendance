<?php

namespace App\Livewire\Rdp\MasterAset;

use App\Repositories\RdpMasterAsetRepo;
use Livewire\Component;

class MasterAsetCreate extends Component
{
    public $data;
    public $form = [];

    public function mount()
    {
        $this->form = [
            ['perlengkapan' => ''],
            ['perlengkapan' => ''],
            ['perlengkapan' => ''],
        ];
    }

    public function addRow()
    {
        $this->form[] = ['perlengkapan' => ''];
    }

    public function removeRow($key)
    {
        unset($this->form[$key]);
        $this->form = array_values($this->form);

        if (count($this->form) === 0) {
            $this->addRow();
        }
    }

    public function rules()
    {
        return [
            'form' => 'required|array|min:1',
            'form.*.perlengkapan' => 'required|distinct|unique:rdp_master_asets,perlengkapan',
        ];
    }

    public function wireSubmit()
    {
        $this->form = collect($this->form)
            ->map(function ($item) {
                return [
                    'perlengkapan' => trim($item['perlengkapan'] ?? ''),
                ];
            })
            ->values()
            ->toArray();

        $validated = $this->validate();

        $items = collect($validated['form'])
            ->map(function ($item) {
                return [
                    'perlengkapan' => $item['perlengkapan'],
                ];
            })
            ->values()
            ->toArray();

        if (RdpMasterAsetRepo::createMultiple($items)) {
            return redirect()
                ->route('rdp.master.aset.index')
                ->with('success', 'Data aset berhasil ditambahkan.');
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        'form.*.perlengkapan' => 'Nama Perlengkapan',
    ];

    public function render()
    {
        return view('rdp.master_aset.master_aset_create');
    }
}
