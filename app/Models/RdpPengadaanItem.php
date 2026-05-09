<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpPengadaanItem extends Model
{
    protected $guarded = [];

    public function rdp_pengadaans(): BelongsTo
    {
        return $this->belongsTo(RdpPengadaan::class, 'rdp_pengadaan_id', 'id');
    }
}
