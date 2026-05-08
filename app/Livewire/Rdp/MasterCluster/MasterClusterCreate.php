<?php

namespace App\Livewire\Rdp\MasterCluster;

use App\Repositories\RdpMasterAsetRepo;
use App\Repositories\RdpMasterClusterRepo;
use App\Repositories\RdpMasterRumahRepo;
use Livewire\Component;

class MasterClusterCreate extends Component
{
    public $data;
    public $dt;
    public $form = [];
    public $detail = [];

    public function mount()
    {
        $this->dt['aset'] = RdpMasterAsetRepo::getAll()->toArray();
        $this->dt['satuan'] = RdpMasterRumahRepo::SATUAN_LIST;
        $this->form = ['nama_cluster' => ''];
        $this->detail = [
            ['aset_id' => '', 'jenis' => '', 'jumlah' => '', 'satuan' => ''],
        ];
    }

    public function addRow()
    {
        $this->detail[] = ['aset_id' => '', 'jenis' => '', 'jumlah' => '', 'satuan' => ''];
    }

    public function removeRow($key)
    {
        unset($this->detail[$key]);
        $this->detail = array_values($this->detail);

        if (count($this->detail) === 0) {
            $this->addRow();
        }
    }

    public function rules()
    {
        return [
            'form.nama_cluster' => 'required|unique:rdp_master_clusters,nama_cluster',
            'detail' => 'required|array|min:1',
            'detail.*.aset_id' => 'required|exists:rdp_master_asets,id',
            'detail.*.jenis' => 'nullable|string',
            'detail.*.jumlah' => 'nullable|string',
            'detail.*.satuan' => 'nullable|in:' . implode(',', RdpMasterRumahRepo::SATUAN_LIST),
        ];
    }

    public function wireSubmit()
    {
        $this->sanitize();
        $validated = $this->validate();

        $data['cluster'] = $validated['form'];
        $data['detail'] = $this->normalizeDetail($validated['detail']);

        if (RdpMasterClusterRepo::create($data)) {
            return redirect()
                ->route('rdp.master.cluster.index')
                ->with('success', 'Data cluster berhasil ditambahkan.');
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    protected function sanitize()
    {
        $this->form['nama_cluster'] = trim($this->form['nama_cluster'] ?? '');
        $this->detail = collect($this->detail)
            ->map(function ($item) {
                return [
                    'aset_id' => $item['aset_id'] ?? '',
                    'jenis' => trim($item['jenis'] ?? ''),
                    'jumlah' => trim($item['jumlah'] ?? ''),
                    'satuan' => $item['satuan'] ?? '',
                ];
            })
            ->values()
            ->toArray();
    }

    protected function normalizeDetail($detail)
    {
        return collect($detail)
            ->map(function ($item) {
                return [
                    'aset_id' => $item['aset_id'],
                    'jenis' => $item['jenis'] ?: null,
                    'jumlah' => $item['jumlah'] ?: null,
                    'satuan' => $item['satuan'] ?: null,
                ];
            })
            ->values()
            ->toArray();
    }

    public $validationAttributes = [
        'form.nama_cluster' => 'Nama Cluster',
        'detail.*.aset_id' => 'Aset / Perlengkapan',
        'detail.*.jenis' => 'Jenis',
        'detail.*.jumlah' => 'Jumlah',
        'detail.*.satuan' => 'Satuan',
    ];

    public function render()
    {
        return view('rdp.master_cluster.master_cluster_create');
    }
}
