<?php

namespace App\Livewire\Rdp\MasterRumah;

use App\Repositories\RdpMasterClusterRepo;
use App\Repositories\RdpMasterRumahRepo;
use Livewire\Component;

class MasterRumahCreate extends Component
{
    public const STATUS_LIST = ['Kosong', 'Terisi', 'Maintenance', 'Tidak Aktif'];

    public $data;
    public $dt;
    public $form = [];

    public function mount()
    {
        $this->dt['cluster'] = RdpMasterClusterRepo::getAll()->toArray();
        $this->dt['status'] = self::STATUS_LIST;
        $this->form = [
            'rdp_master_cluster_id' => '',
            'block' => '',
            'tipe' => '',
            'nomor' => '',
            'status' => 'Kosong',
        ];
    }

    public function rules()
    {
        return [
            'form.rdp_master_cluster_id' => 'required|exists:rdp_master_clusters,id',
            'form.block' => 'nullable|string',
            'form.tipe' => 'nullable|string',
            'form.nomor' => 'required|string',
            'form.status' => 'required|in:' . implode(',', self::STATUS_LIST),
        ];
    }

    public function wireSubmit()
    {
        $this->sanitize();
        $validated = $this->validate();

        if (RdpMasterRumahRepo::create($validated['form'])) {
            return redirect()
                ->route('rdp.master.rumah.index')
                ->with('success', 'Data unit rumah berhasil ditambahkan.');
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    protected function sanitize()
    {
        $this->form['block'] = trim($this->form['block'] ?? '');
        $this->form['tipe'] = trim($this->form['tipe'] ?? '');
        $this->form['nomor'] = trim($this->form['nomor'] ?? '');
    }

    public $validationAttributes = [
        'form.rdp_master_cluster_id' => 'Cluster',
        'form.block' => 'Block',
        'form.tipe' => 'Tipe',
        'form.nomor' => 'Nomor',
        'form.status' => 'Status',
    ];

    public function render()
    {
        return view('rdp.master_rumah.master_rumah_create');
    }
}
