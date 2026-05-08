<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpMasterRumah extends Model
{
    protected $guarded = [];

    public function rdp_master_clusters(): BelongsTo
    {
        return $this->belongsTo(RdpMasterCluster::class, 'rdp_master_cluster_id', 'id');
    }

    public function rdp_master_rumah_asets(): HasMany
    {
        return $this->hasMany(RdpMasterRumahAset::class, 'rdp_master_rumah_id', 'id');
    }

    public function rdp_karyawan_masuks(): HasMany
    {
        return $this->hasMany(RdpKaryawanMasuk::class, 'rdp_master_rumah_id', 'id');
    }

    public function rdp_karyawan_keluars(): HasMany
    {
        return $this->hasMany(RdpKaryawanKeluar::class, 'rdp_master_rumah_id', 'id');
    }
}
