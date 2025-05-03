<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSchedule extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data_employees(): BelongsToMany
    {
        return $this->belongsToMany(
            DataEmployee::class,
            'rel_data_employee_master_schedules',
            'master_schedule_id',
            'data_employee_id'
        )
        ->using(RelDataEmployeeMasterSchedule::class)
        ->withPivot(['effective_at', 'expired_at'])
        ->wherePivotNull('deleted_at')
        ->withTimestamps();
    }

    protected function dayWork(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode($value),
        );
    }
}
