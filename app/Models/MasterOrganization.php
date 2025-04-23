<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOrganization extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data_employees():HasMany
    {
        return $this->hasMany(DataEmployee::class, 'master_organization_id', 'id');
    }
}
