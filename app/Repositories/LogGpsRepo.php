<?php

namespace App\Repositories;

use App\Models\LogGps;
use App\Repositories\Interfaces\LogGpsFace;
use Carbon\Carbon;

class LogGpsRepo implements LogGpsFace
{
    public function store(array $data)
    {
        return LogGps::insert($data);
    }

    // public function getLastTimeByMachine($data)
    // {
    //     return LogAttendance::where('master_machine_id', $data)->max('time');
    // }
}
