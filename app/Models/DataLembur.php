<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataLembur extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data_employee_admins():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'approved_by', 'id');
    }
    public function data_employees():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'data_employee_id', 'id');
    }
    public function pengawas1():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'pengawas1', 'id');
    }
    public function pengawas2():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'pengawas2', 'id');
    }
    public function security():BelongsTo
    {
        return $this->belongsTo(DataEmployee::class, 'security', 'id');
    }
}
