<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpMasterRumahAset extends Model
{
    protected $guarded = [];

    public function rdp_master_rumahs(): BelongsTo
    {
        return $this->belongsTo(RdpMasterRumah::class, 'rdp_master_rumah_id', 'id');
    }

    public function rdp_master_asets(): BelongsTo
    {
        return $this->belongsTo(RdpMasterAset::class, 'rdp_master_aset_id', 'id');
    }

}
