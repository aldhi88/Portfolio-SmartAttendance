<?php

namespace App\Repositories;

use App\Models\DataAttendanceClaim;
use Illuminate\Support\Facades\Log;

class DataAttendanceClaimRepo
{
    public static function bulkInsert($data)
    {
        try {
            if (empty($data) || !isset($data[0])) {
                return false;
            }

            $first = $data[0];

            if (array_key_exists('data_lembur_id', $first)) {
                DataAttendanceClaim::where(
                    'data_lembur_id',
                    $first['data_lembur_id']
                )->forceDelete();
            } else {
                DataAttendanceClaim::where(
                    'absen_date',
                    $first['absen_date']
                )->forceDelete();
            }

            DataAttendanceClaim::insert($data);

            return true;
        } catch (\Exception $e) {
            Log::error('Insert bulk log_gps failed', [
                'error' => $e->getMessage(),
                'payload' => $data[0] ?? null
            ]);

            return false;
        }
    }
}
