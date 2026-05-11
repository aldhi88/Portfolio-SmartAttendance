<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RdpMasterVendor extends Model
{
    protected $guarded = [];

    public function user_logins(): BelongsTo
    {
        return $this->belongsTo(UserLogin::class, 'user_login_id', 'id');
    }

    public function rdp_perbaikans(): HasMany
    {
        return $this->hasMany(RdpPerbaikan::class, 'rdp_master_vendor_id', 'id');
    }

    public function rdp_pengadaans(): HasMany
    {
        return $this->hasMany(RdpPengadaan::class, 'rdp_master_vendor_id', 'id');
    }
}
