<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterMachine extends Model
{
    protected $guarded = [];

    public function master_locations(): BelongsTo
    {
        return $this->belongsTo(MasterLocation::class, 'master_location_id', 'id');
    }
}
