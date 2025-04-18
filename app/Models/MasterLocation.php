<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterLocation extends Model
{
    protected $guarded = [];

    public function master_locations(): HasMany
    {
        return $this->hasMany(MasterMachine::class, 'master_location_id', 'id');
    }
}
