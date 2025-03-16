<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class LaravelPersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $table = 'laravel_personal_access_tokens';
}
