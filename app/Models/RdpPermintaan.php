<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpPermintaan extends Model
{
    protected $guarded = [];

    public function rdp_karyawan_masuks(): BelongsTo
    {
        return $this->belongsTo(RdpKaryawanMasuk::class, 'rdp_karyawan_masuk_id', 'id');
    }

    public function rdp_permintaan_items(): HasMany
    {
        return $this->hasMany(RdpPermintaanItem::class, 'rdp_permintaan_id', 'id');
    }
}
