<?php

namespace App\Repositories\Interfaces;

interface LogAttendanceInterface
{
    public function store(array $data);
    public function getLastTimeByMachine($data);
}