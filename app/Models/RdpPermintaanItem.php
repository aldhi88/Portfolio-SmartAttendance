<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpPermintaanItem extends Model
{
    protected $guarded = [];

    public function rdp_permintaans(): BelongsTo
    {
        return $this->belongsTo(RdpPermintaan::class, 'rdp_permintaan_id', 'id');
    }
}
