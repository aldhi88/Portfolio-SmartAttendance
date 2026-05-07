<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpMasterCluster extends Model
{
    protected $guarded = [];

    public function rdp_master_cluster_master_asets(): HasMany
    {
        return $this->hasMany(RdpMasterClusterMasterAset::class, 'cluster_id', 'id');
    }
}
