<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataEmployee extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function data_employees():BelongsTo
    {
        return $this->belongsTo(MasterOrganization::class, 'master_organization_id', 'id');
    }
}
