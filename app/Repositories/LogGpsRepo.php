<?php

namespace App\Repositories;

use App\Models\LogGps;
use App\Repositories\Interfaces\LogGpsFace;

class LogGpsRepo implements LogGpsFace
{
    public function store(array $data)
    {
        return LogGps::create($data);
    }

    public static function getLogByEmployeeId($data)
    {

        return LogGps::where('data_employee_id', $data['employeeId'])
            ->whereBetween('created_at', [
                $data['start'], $data['end']
            ])
            ->get()
            ->toArray();
    }

}
