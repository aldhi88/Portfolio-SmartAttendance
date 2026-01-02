<?php

namespace App\Repositories;

use App\Models\LogGps;
use App\Repositories\Interfaces\LogGpsFace;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    public static function bulkInsert($data)
    {
        try {
            LogGps::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert bulk log_gps failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
