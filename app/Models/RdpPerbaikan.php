<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpPerbaikan extends Model
{
    protected $guarded = [];

    public function rdp_karyawan_masuks(): BelongsTo
    {
        return $this->belongsTo(RdpKaryawanMasuk::class, 'rdp_karyawan_masuk_id', 'id');
    }

    public function rdp_master_vendors(): BelongsTo
    {
        return $this->belongsTo(RdpMasterVendor::class, 'rdp_master_vendor_id', 'id');
    }

    public function rdp_perbaikan_items(): HasMany
    {
        return $this->hasMany(RdpPerbaikanItem::class, 'rdp_perbaikan_id', 'id');
    }
}
