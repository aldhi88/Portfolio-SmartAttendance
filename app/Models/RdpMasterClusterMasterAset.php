<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpMasterClusterMasterAset extends Model
{
    protected $guarded = [];

    public function rdp_master_clusters(): BelongsTo
    {
        return $this->belongsTo(RdpMasterCluster::class, 'cluster_id', 'id');
    }

    public function rdp_master_asets(): BelongsTo
    {
        return $this->belongsTo(RdpMasterAset::class, 'aset_id', 'id');
    }
}
