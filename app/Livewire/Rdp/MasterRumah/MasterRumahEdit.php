<?php

namespace App\Livewire\Rdp\MasterRumah;

use App\Repositories\RdpMasterClusterRepo;
use App\Repositories\RdpMasterAsetRepo;
use App\Repositories\RdpMasterRumahRepo;
use Livewire\Component;

class MasterRumahEdit extends Component
{
    public const STATUS_LIST = ['Kosong', 'Terisi', 'Maintenance', 'Tidak Aktif'];
    public const ASET_STATUS_LIST = ['Ada', 'Tidak Ada'];

    public $data;
    public $dt;
    public $form = [];
    public $aset = [];
    public $editId;
    public $originalClusterId;

    public function mount()
    {
        $this->editId = $this->data['id'];
        $this->dt['cluster'] = RdpMasterClusterRepo::getAll()->toArray();
        $this->dt['aset'] = RdpMasterAsetRepo::getAll()->toArray();
        $this->dt['status'] = self::STATUS_LIST;
        $this->dt['aset_status'] = self::ASET_STATUS_LIST;

        $rumah = RdpMasterRumahRepo::getByKey($this->editId);
        $this->originalClusterId = (string) $rumah->rdp_master_cluster_id;
        $this->form = [
            'rdp_master_cluster_id' => (string) $rumah->rdp_master_cluster_id,
            'block' => $rumah->block,
            'tipe' => $rumah->tipe,
            'nomor' => $rumah->nomor,
            'status' => $rumah->status,
        ];
        $this->aset = $rumah->rdp_master_rumah_asets
            ->map(function ($item) {
                return [
                    'rdp_master_aset_id' => (string) $item->rdp_master_aset_id,
                    'jenis' => $item->jenis,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->satuan,
                    'status' => $item->status,
                    'catatan' => $item->catatan,
                ];
            })
            ->values()
            ->toArray();

    }

    public function addAsetRow()
    {
        $this->aset[] = [
            'rdp_master_aset_id' => '',
            'jenis' => '',
            'jumlah' => '',
            'satuan' => '',
            'status' => 'Ada',
            'catatan' => '',
        ];
    }

    public function removeAsetRow($key)
    {
        unset($this->aset[$key]);
        $this->aset = array_values($this->aset);

    }

    public function rules()
    {
        return [
            'form.rdp_master_cluster_id' => 'required|exists:rdp_master_clusters,id',
            'form.block' => 'nullable|string',
            'form.tipe' => 'nullable|string',
            'form.nomor' => 'required|string',
            'form.status' => 'required|in:' . implode(',', self::STATUS_LIST),
            'aset' => 'array',
            'aset.*.rdp_master_aset_id' => 'required|exists:rdp_master_asets,id',
            'aset.*.jenis' => 'nullable|string',
            'aset.*.jumlah' => 'nullable|string',
            'aset.*.satuan' => 'nullable|string',
            'aset.*.status' => 'required|in:' . implode(',', self::ASET_STATUS_LIST),
            'aset.*.catatan' => 'nullable|string',
        ];
    }

    public function wireSubmit()
    {
        $this->sanitize();
        $validated = $this->validate();

        $data['rumah'] = $validated['form'];
        $data['aset'] = $this->normalizeAset($validated['aset']);

        if (RdpMasterRumahRepo::update($this->editId, $data)) {
            return redirect()
                ->route('rdp.master.rumah.index')
                ->with('success', 'Perubahan data unit rumah berhasil disimpan.');
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    protected function sanitize()
    {
        $this->form['block'] = trim($this->form['block'] ?? '');
        $this->form['tipe'] = trim($this->form['tipe'] ?? '');
        $this->form['nomor'] = trim($this->form['nomor'] ?? '');
        $this->aset = collect($this->aset)
            ->map(function ($item) {
                return [
                    'rdp_master_aset_id' => $item['rdp_master_aset_id'] ?? '',
                    'jenis' => trim($item['jenis'] ?? ''),
                    'jumlah' => trim($item['jumlah'] ?? ''),
                    'satuan' => trim($item['satuan'] ?? ''),
                    'status' => $item['status'] ?? 'Ada',
                    'catatan' => trim($item['catatan'] ?? ''),
                ];
            })
            ->filter(function ($item) {
                return $item['rdp_master_aset_id'] !== '';
            })
            ->values()
            ->toArray();
    }

    protected function normalizeAset($aset)
    {
        return collect($aset)
            ->map(function ($item) {
                return [
                    'rdp_master_aset_id' => $item['rdp_master_aset_id'],
                    'jenis' => $item['jenis'] ?: null,
                    'jumlah' => $item['jumlah'] ?: null,
                    'satuan' => $item['satuan'] ?: null,
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?: null,
                ];
            })
            ->values()
            ->toArray();
    }

    public $validationAttributes = [
        'form.rdp_master_cluster_id' => 'Cluster',
        'form.block' => 'Block',
        'form.tipe' => 'Tipe',
        'form.nomor' => 'Nomor',
        'form.status' => 'Status',
        'aset.*.rdp_master_aset_id' => 'Aset / Perlengkapan',
        'aset.*.jenis' => 'Jenis',
        'aset.*.jumlah' => 'Jumlah',
        'aset.*.satuan' => 'Satuan',
        'aset.*.status' => 'Status Aset',
        'aset.*.catatan' => 'Catatan',
    ];

    public function render()
    {
        return view('rdp.master_rumah.master_rumah_edit');
    }
}
