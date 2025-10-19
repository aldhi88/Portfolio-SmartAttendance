<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;


class DataSchedulesBebas extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function master_schedules(): BelongsTo
    {
        return $this->belongsTo(MasterSchedule::class, 'master_schedule_id', 'id');
    }

    protected function dayWork(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode($value),
        );
    }
}
