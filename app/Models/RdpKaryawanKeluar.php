<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RdpKaryawanKeluar extends Model
{
    protected $guarded = [];

    public function data_employees(): BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'data_employee_id', 'id');
    }

    public function rdp_master_rumahs(): BelongsTo
    {
        return $this->belongsTo(RdpMasterRumah::class, 'rdp_master_rumah_id', 'id');
    }

}
