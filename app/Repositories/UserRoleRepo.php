<?php

namespace App\Repositories;

use App\Models\UserRole;
use App\Repositories\Interfaces\UserRoleFace;

class UserRoleRepo implements UserRoleFace
{
    // protected $dataEmployee;

    // public function __construct(DataEmployeeFace $dataEmployee)
    // {
    //     $this->dataEmployee = $dataEmployee;
    // }

    public function getAll()
    {
        return UserRole::all();
    }

}
