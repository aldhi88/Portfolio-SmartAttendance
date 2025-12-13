<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataVendor extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function user_logins(): BelongsTo
    {
        return $this->belongsTo(UserLogin::class, 'user_login_id', 'id');
    }
    public function master_organizations(): BelongsTo
    {
        return $this->belongsTo(MasterOrganization::class, 'master_organization_id', 'id');
    }

}
