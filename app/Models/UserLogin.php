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
    protected $appends = ['is_pengawas'];

    public function user_roles(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'user_role_id', 'id');
    }
    public function data_employees(): HasOne
    {
        return $this->hasOne(DataEmployee::class, 'user_login_id', 'id');
    }

    public function getIsPengawasAttribute()
    {
        return in_array($this->user_role_id, [200, 400]);
    }
}
