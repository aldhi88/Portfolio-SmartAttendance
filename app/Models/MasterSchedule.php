<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


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

    protected function kode(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }
    protected function dayWork(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode($value),
        );
    }
    protected function checkinTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('H:i:s', $value)->format('H:i'),
        );
    }
    protected function workTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('H:i:s', $value)->format('H:i'),
        );
    }
    protected function checkinDeadlineTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('H:i:s', $value)->format('H:i'),
        );
    }
    protected function checkoutTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('H:i:s', $value)->format('H:i'),
        );
    }
}
