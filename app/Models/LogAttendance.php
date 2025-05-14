<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAttendance extends Model
{
    protected $guarded = [];

    public function data_employee():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'data_employee_id', 'id');
    }
    public function master_machines():BelongsTo
    {
        return $this->belongsTo(MasterMachine::class, 'master_machine_id', 'id');
    }
    public function master_minors():BelongsTo
    {
        return $this->belongsTo(MasterMinor::class, 'master_minor_id', 'id');
    }
}
