<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataEmployee extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function master_schedules(): BelongsToMany
    {
        return $this->belongsToMany(
            MasterSchedule::class,
            'rel_data_employee_master_schedules',
            'data_employee_id',
            'master_schedule_id'
        )
        ->withPivot(['effective_at', 'expired_at'])
        ->wherePivotNull('deleted_at')
        ->withTimestamps();
    }

    public function user_logins():BelongsTo
    {
        return $this->belongsTo(UserLogin::class, 'user_login_id', 'id');
    }
    public function master_organizations():BelongsTo
    {
        return $this->belongsTo(MasterOrganization::class, 'master_organization_id', 'id');
    }
    public function master_positions():BelongsTo
    {
        return $this->belongsTo(MasterPosition::class, 'master_position_id', 'id');
    }
    public function master_locations():BelongsTo
    {
        return $this->belongsTo(MasterLocation::class, 'master_location_id', 'id');
    }
    public function master_functions():BelongsTo
    {
        return $this->belongsTo(MasterFunction::class, 'master_function_id', 'id');
    }
    public function log_attendances():HasMany
    {
        return $this->hasMany(LogAttendance::class, 'data_employee_id', 'id');
    }


}
