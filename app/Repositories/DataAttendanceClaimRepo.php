<?php

namespace App\Repositories;

use App\Models\DataAttendanceClaim;
use Illuminate\Support\Facades\Log;

class DataAttendanceClaimRepo
{
    public static function bulkInsert($data)
    {
        try {
            DataAttendanceClaim::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert bulk log_gps failed", ['error' => $e->getMessage()]);
            return false;
        }
    }


}
