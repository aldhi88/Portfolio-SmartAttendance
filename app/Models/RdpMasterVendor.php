<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpMasterVendor extends Model
{
    protected $guarded = [];

    public function user_logins(): BelongsTo
    {
        return $this->belongsTo(UserLogin::class, 'user_login_id', 'id');
    }
}
