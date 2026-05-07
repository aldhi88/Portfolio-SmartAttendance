<?php

namespace App\Livewire\Rdp\MasterCluster;

use App\Repositories\RdpMasterClusterRepo;
use Livewire\Component;

class MasterClusterDetail extends Component
{
    public $data;
    public $cluster;

    public function mount()
    {
        $this->cluster = RdpMasterClusterRepo::getByKey($this->data['id']);
    }

    public function render()
    {
        return view('rdp.master_cluster.master_cluster_detail');
    }
}
