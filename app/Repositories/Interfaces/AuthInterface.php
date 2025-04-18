<?php

namespace App\Repositories\Interfaces;

interface AuthInterface
{
    public function login($data);
    public function logout();
}
