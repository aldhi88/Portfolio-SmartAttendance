<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpPengadaan extends Model
{
    protected $guarded = [];

    public function rdp_master_vendors()
    {
        return $this->belongsTo(RdpMasterVendor::class, 'rdp_master_vendor_id', 'id');
    }

    public function rdp_karyawan_masuks(): BelongsTo
    {
        return $this->belongsTo(RdpKaryawanMasuk::class, 'rdp_karyawan_masuk_id', 'id');
    }

    public function rdp_pengadaan_items(): HasMany
    {
        return $this->hasMany(RdpPengadaanItem::class, 'rdp_pengadaan_id', 'id');
    }
}
