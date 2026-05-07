<?php

namespace App\Livewire\Rdp\MasterCluster;

use App\Repositories\RdpMasterAsetRepo;
use App\Repositories\RdpMasterClusterRepo;
use Livewire\Component;

class MasterClusterEdit extends Component
{
    public const SATUAN_LIST = ['Unit', 'Set', 'Buah', 'Paket', 'Menyesuaikan'];

    public $data;
    public $dt;
    public $form = [];
    public $detail = [];
    public $editId;

    public function mount()
    {
        $this->editId = $this->data['id'];
        $this->dt['aset'] = RdpMasterAsetRepo::getAll()->toArray();
        $this->dt['satuan'] = self::SATUAN_LIST;

        $cluster = RdpMasterClusterRepo::getByKey($this->editId);
        $this->form = [
            'nama_cluster' => $cluster->nama_cluster,
        ];
        $this->detail = $cluster->rdp_master_cluster_master_asets
            ->map(function ($item) {
                return [
                    'aset_id' => (string) $item->aset_id,
                    'jenis' => $item->jenis,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->satuan,
                ];
            })
            ->values()
            ->toArray();

        if (count($this->detail) === 0) {
            $this->addRow();
        }
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
            "form.nama_cluster" => "required|unique:rdp_master_clusters,nama_cluster,{$this->editId},id",
            'detail' => 'required|array|min:1',
            'detail.*.aset_id' => 'required|distinct|exists:rdp_master_asets,id',
            'detail.*.jenis' => 'nullable|string',
            'detail.*.jumlah' => 'nullable|string',
            'detail.*.satuan' => 'nullable|in:'.implode(',', self::SATUAN_LIST),
        ];
    }

    public function wireSubmit()
    {
        $this->sanitize();
        $validated = $this->validate();

        $data['cluster'] = $validated['form'];
        $data['detail'] = $this->normalizeDetail($validated['detail']);

        if (RdpMasterClusterRepo::update($this->editId, $data)) {
            return redirect()
                ->route('rdp.master.cluster.index')
                ->with('success', 'Perubahan data cluster berhasil disimpan.');
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
        return view('rdp.master_cluster.master_cluster_edit');
    }
}
