<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserLogin extends Authenticatable
{
    use HasApiTokens;
    use SoftDeletes;
    protected $guarded = [];
    protected $appends = [
        'is_superuser',
        'is_pengawas',
        'is_karyawan',
        'is_pengawas_rdp',
        'is_manajer',
        'is_vendor',
        'is_vendorrdp',

        'is_pengawas_karyawan',
        'is_pengawas_karyawan_pengawasrdp',
        'is_pengawas_manajer',
    ];

    public function user_roles(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'user_role_id', 'id');
    }
    public function data_employees(): HasOne
    {
        return $this->hasOne(DataEmployee::class, 'user_login_id', 'id');
    }
    public function data_vendors(): HasOne
    {
        return $this->hasOne(DataVendor::class, 'user_login_id', 'id');
    }
    public function rdp_master_vendors(): HasOne
    {
        return $this->hasOne(RdpMasterVendor::class, 'user_login_id', 'id');
    }


    public function getIsSuperuserAttribute()
    {
        return in_array($this->user_role_id, [100]);
    }
    public function getIsPengawasAttribute()
    {
        return in_array($this->user_role_id, [200]);
    }
    public function getIsKaryawanAttribute()
    {
        return in_array($this->user_role_id, [300]);
    }
    public function getIsPengawasRdpAttribute()
    {
        return in_array($this->user_role_id, [400]);
    }
    public function getIsManajerAttribute()
    {
        return in_array($this->user_role_id, [500]);
    }
    public function getIsVendorAttribute()
    {
        return in_array($this->user_role_id, [600]);
    }
    public function getIsVendorRdpAttribute()
    {
        return in_array($this->user_role_id, [700]);
    }


    public function getIsPengawasKaryawanAttribute()
    {
        return in_array($this->user_role_id, [200, 300]);
    }
    public function getIsPengawasKaryawanPengawasRdpAttribute()
    {
        return in_array($this->user_role_id, [200, 300, 400]);
    }
    public function getIsPengawasManajerAttribute()
    {
        return in_array($this->user_role_id, [200, 500]);
    }



}
