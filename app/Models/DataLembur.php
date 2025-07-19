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
}
