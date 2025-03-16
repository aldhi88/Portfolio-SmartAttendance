<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserRole extends Authenticatable
{
    protected $guarded = [];
    public $timestamps = false;


    public function user_logins(): HasMany
    {
        return $this->hasMany(UserLogin::class, 'user_role_id', 'id');
    }
}
