<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelDataEmployeeMasterSchedule extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data_employees(): BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'data_employee_id', 'id');
    }
    public function master_schedules(): BelongsTo
    {
        return $this->belongsTo(MasterSchedule::class, 'master_schedule_id', 'id');
    }
}
