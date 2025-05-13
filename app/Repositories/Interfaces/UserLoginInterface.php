<?php

namespace App\Repositories\Interfaces;

interface UserLoginInterface
{
    public function getByUsername($data);
    public function create($data);
    public function update($id, $data);
}
