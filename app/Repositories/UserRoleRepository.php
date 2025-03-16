<?php

namespace App\Repositories;

use App\Models\UserRole;
use App\Repositories\Interfaces\UserRoleInterface;

class UserRoleRepository implements UserRoleInterface
{
    public function getAll()
    {
        return UserRole::all();
    }
}
