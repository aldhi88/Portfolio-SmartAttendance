<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataSchedulesBebas extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function master_schedules(): BelongsTo
    {
        return $this->belongsTo(MasterSchedule::class, 'master_schedule_id', 'id');
    }
}
