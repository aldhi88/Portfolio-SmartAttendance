<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpPerbaikanItem extends Model
{
    protected $guarded = [];

    public function rdp_perbaikans(): BelongsTo
    {
        return $this->belongsTo(RdpPerbaikan::class, 'rdp_perbaikan_id', 'id');
    }
}
