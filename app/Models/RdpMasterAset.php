<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpMasterAset extends Model
{
    protected $guarded = [];

    public function rdp_master_cluster_master_asets(): HasMany
    {
        return $this->hasMany(RdpMasterClusterMasterAset::class, 'aset_id', 'id');
    }

    public function rdp_master_rumah_asets(): HasMany
    {
        return $this->hasMany(RdpMasterRumahAset::class, 'rdp_master_aset_id', 'id');
    }
}
