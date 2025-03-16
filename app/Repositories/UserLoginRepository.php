<?php

namespace App\Repositories;

use App\Models\UserLogin;
use App\Repositories\Interfaces\UserLoginInterface;

class UserLoginRepository implements UserLoginInterface
{
    public function getByUsername($data)
    {
        return UserLogin::with('user_roles')
            ->where('username', $data)->first();
    }
}
