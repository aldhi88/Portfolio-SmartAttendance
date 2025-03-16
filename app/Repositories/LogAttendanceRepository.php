<?php

namespace App\Repositories;

use App\Models\LogAttendance;
use App\Repositories\Interfaces\LogAttendanceInterface;

class LogAttendanceRepository implements LogAttendanceInterface
{
    public function store(array $data)
    {
        return LogAttendance::insert($data);
    }

    public function getLastTimeByMachine($data)
    {
        return LogAttendance::where('master_machine_id', $data)->max('time');
    }
}
