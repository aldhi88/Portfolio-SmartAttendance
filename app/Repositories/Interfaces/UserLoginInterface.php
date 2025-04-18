<?php

namespace App\Repositories\Interfaces;

interface UserLoginInterface
{
    public function getByUsername($data);
}
