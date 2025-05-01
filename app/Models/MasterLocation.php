<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLocation extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data_employees():HasMany
    {
        return $this->hasMany(DataEmployee::class, 'master_location_id', 'id');
    }
    public function master_locations(): HasMany
    {
        return $this->hasMany(MasterMachine::class, 'master_location_id', 'id');
    }
}
