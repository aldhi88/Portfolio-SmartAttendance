<?php

namespace App\Repositories;

use App\Models\DataAttendanceClaim;
use Illuminate\Support\Facades\Log;

class DataAttendanceClaimRepo
{
    public static function bulkInsert($data)
    {
        try {
            DataAttendanceClaim::where('data_lembur_id', $data[0]['data_lembur_id'])->forceDelete();
            DataAttendanceClaim::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert bulk log_gps failed", ['error' => $e->getMessage()]);
            return false;
        }
    }


}
